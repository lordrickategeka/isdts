<?php

namespace App\Imports;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;

class ClientsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    protected $projectId;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            Log::info('ClientsImport: Processing row', [
                'customer_name' => $row['customer_name'] ?? 'N/A',
                'row_data' => $row
            ]);

            DB::beginTransaction();

            // Process latitude - handle comma-separated coordinates
            $latitude = null;
            if (!empty($row['latitude'])) {
                if (is_numeric($row['latitude'])) {
                    $latitude = $row['latitude'];
                } elseif (is_string($row['latitude']) && strpos($row['latitude'], ',') !== false) {
                    // Extract first part of comma-separated coordinates
                    $coords = explode(',', $row['latitude']);
                    $latitude = is_numeric(trim($coords[0])) ? trim($coords[0]) : null;
                }
            }

            // Process VLAN - convert to string if numeric
            $vlan = null;
            if (isset($row['vlan'])) {
                $vlan = is_numeric($row['vlan']) ? (string)(int)$row['vlan'] : $row['vlan'];
            }

            // Process auth_date - handle Excel serial dates
            $installationDate = null;
            if (!empty($row['auth_date'])) {
                if (is_numeric($row['auth_date'])) {
                    // Convert Excel serial date to PHP DateTime
                    // Excel dates start from 1900-01-01, but PHP dates from 1970-01-01
                    // Excel serial 1 = 1900-01-01, serial 25569 = 1970-01-01
                    try {
                        $unixTimestamp = ($row['auth_date'] - 25569) * 86400;
                        $installationDate = date('Y-m-d', $unixTimestamp);
                    } catch (\Exception $e) {
                        Log::warning('ClientsImport: Failed to convert Excel date', [
                            'auth_date' => $row['auth_date'],
                            'error' => $e->getMessage()
                        ]);
                    }
                } else {
                    // Assume it's already a valid date string
                    $installationDate = $row['auth_date'];
                }
            }

            // Check if client exists by customer_name (unique) or create new
            $client = Client::firstOrCreate(
                ['customer_name' => $row['customer_name']],
                [
                    'category' => $row['customer_type'] ?? 'Home',
                    'phone' => $row['phone'] ?? null,
                    'email' => $row['email'] ?? null,
                    'address' => $row['address'] ?? null,
                    'latitude' => $latitude,
                    'longitude' => $row['longitude'] ?? null,
                    'region' => $row['region'] ?? null,
                    'district' => $row['district'] ?? null,
                    'contact_person' => $row['installation_engineer'] ?? null,
                    'created_by' => Auth::user()->id,
                ]
            );

            Log::info('ClientsImport: Client processed', [
                'client_id' => $client->id,
                'customer_name' => $client->customer_name,
                'was_created' => $client->wasRecentlyCreated
            ]);

            // Get vendor ID (prepopulated as BCC)
            $vendor = null;
            if (isset($row['vendor_id'])) {
                $vendor = \App\Models\Vendor::find($row['vendor_id']);
                Log::info('ClientsImport: Vendor lookup by ID', [
                    'vendor_id' => $row['vendor_id'],
                    'found' => $vendor ? 'Yes' : 'No'
                ]);
            } else {
                $vendor = \App\Models\Vendor::where('name', 'like', '%BCC%')->first();
                Log::info('ClientsImport: Vendor lookup by name (BCC)', [
                    'found' => $vendor ? 'Yes' : 'No'
                ]);
            }
            $vendorId = $vendor?->id;
            $vendorName = $vendor?->name;

            // Get product ID for transmission (prepopulated as Fiber)
            $product = null;
            if (isset($row['transmission_product_id'])) {
                $product = Product::find($row['transmission_product_id']);
                Log::info('ClientsImport: Product lookup by ID', [
                    'product_id' => $row['transmission_product_id'],
                    'found' => $product ? 'Yes' : 'No'
                ]);
            } else {
                $product = Product::where('name', 'Fiber')->first();
                Log::info('ClientsImport: Product lookup by name (Fiber)', [
                    'found' => $product ? 'Yes' : 'No'
                ]);
            }
            $productId = $product?->id;
            $productName = $product?->name ?? 'Fiber';
            $serviceType = $productName;

            // Map administrative_status to status
            $status = 'active'; // default
            if (isset($row['administrative_status'])) {
                $status = strtolower($row['administrative_status']) === 'enabled' ? 'active' : 'inactive';
            }

            Log::info('ClientsImport: Creating client service', [
                'client_id' => $client->id,
                'project_id' => $this->projectId,
                'vendor_id' => $vendorId,
                'vendor_name' => $vendorName,
                'product_id' => $productId,
                'product_name' => $productName,
                'username' => $row['username'] ?? null,
                'serial_number' => $row['serial_number'] ?? null,
                'capacity' => $row['capacity'] ?? null,
                'capacity_type' => $row['capacity_type'] ?? 'Shared',
                'vlan' => $vlan,
                'installation_date' => $installationDate,
                'status' => $status
            ]);

            // Create or update client service for this project
            ClientService::updateOrCreate(
                [
                    'client_id' => $client->id,
                    'project_id' => $this->projectId,
                ],
                [
                    'vendor_id' => $vendorId,
                    'vendor_name' => $vendorName,
                    'product_id' => $productId,
                    'product_name' => $productName,
                    'service_type' => $serviceType,
                    'username' => $row['username'] ?? null,
                    'serial_number' => $row['serial_number'] ?? null,
                    'capacity' => $row['capacity'] ?? null,
                    'capacity_type' => $row['capacity_type'] ?? 'Shared', // prepopulated
                    'vlan' => $vlan,
                    'nrc' => $row['nrc'] ?? 0,
                    'mrc' => $row['mrc'] ?? 0,
                    'installation_date' => $installationDate, // Use processed date
                    'status' => $status,
                ]
            );

            DB::commit();
            $this->importedCount++;

            Log::info('ClientsImport: Row processed successfully', [
                'customer_name' => $client->customer_name,
                'total_imported' => $this->importedCount
            ]);

            return $client;
        } catch (\Exception $e) {
            DB::rollBack();

            $errorMessage = "Row error: " . $e->getMessage();
            $this->errors[] = $errorMessage;

            Log::error('ClientsImport: Failed to process row', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'row_data' => $row,
                'customer_name' => $row['customer_name'] ?? 'N/A',
                'project_id' => $this->projectId
            ]);

            return null;
        }
    }

    public function rules(): array
    {
        return [
            'customer_name' => 'required|string',
            'customer_type' => 'nullable|in:Home,Corporate',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable|numeric',
            'region' => 'nullable|string',
            'district' => 'nullable|string',
            'installation_engineer' => 'nullable|string',
            'vendor_id' => 'nullable|exists:vendors,id',
            'transmission_product_id' => 'nullable|exists:products,id',
            'username' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'capacity' => 'nullable|string',
            'capacity_type' => 'nullable|string',
            'vlan' => 'nullable', // Allow numeric or string
            'nrc' => 'nullable|numeric',
            'mrc' => 'nullable|numeric',
            'auth_date' => 'nullable', // Allow any format, will be processed in model()
            'administrative_status' => 'nullable|in:Enabled,Disabled,enabled,disabled',
        ];
    }

    /**
     * Handle validation failures and log them
     */
    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            Log::error('ClientsImport: Validation failed', [
                'row_number' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
                'values' => $failure->values()
            ]);
        }
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }
}
