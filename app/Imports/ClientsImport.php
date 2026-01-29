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
    /**
     * Normalize a header string to snake_case for matching.
     */
    protected function normalizeHeader($header)
    {
        // Lowercase, remove non-alphanum, replace spaces/underscores with _
        $header = strtolower($header);
        $header = preg_replace('/[^a-z0-9]+/', '_', $header);
        $header = trim($header, '_');
        return $header;
    }

    /**
     * Normalize all headers in the row to expected keys.
     */
    protected function normalizeRowKeys($row)
    {
        $normalized = [];
        foreach ($row as $key => $value) {
            $normKey = $this->normalizeHeader($key);
            $normalized[$normKey] = $value;
        }
        return $normalized;
    }
    /**
     * Get the number of successfully imported clients.
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Get the errors encountered during import.
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Validation rules for each row in the import.
     * You can customize these rules as needed.
     */
    public function rules(): array
    {
        return [
            // Example: 'customer_name' => 'required|string',
        ];
    }
    use SkipsFailures;

    protected $projectId;
    protected $vendorId;
    protected $customerType;
    protected $importedCount = 0;
    protected $errors = [];

    public function __construct($projectId, $vendorId, $customerType)
    {
        $this->projectId = $projectId;
        $this->vendorId = $vendorId;
        $this->customerType = $customerType;
    }

    /**
     * Static version of normalizeHeader for use outside instance context.
     */
    public static function normalizeHeaderStatic($header)
    {
        $header = strtolower($header);
        $header = preg_replace('/[^a-z0-9]+/', '_', $header);
        $header = trim($header, '_');
        return $header;
    }


    public static function expectedHeaders(): array
    {
        // Expect all fields, but allow them to be null if missing in the file
        return [
            'customer_name',
            'phone',
            'email',
            'address',
            'latitude',
            'longitude',
            'region',
            'district',
            'installation_engineer',
            'transmission',
            'username',
            'serial_number',
            'capacity',
            'capacity_type',
            'vlan',
            'nrc',
            'mrc',
            'auth_date',
            'status',
        ];
    }

    /**
     * Validate headers and log mismatches.
     */
    public function prepareForValidation($data, $index)
    {

        // Normalize all keys in the row
        $data = $this->normalizeRowKeys($data);
        $expected = self::expectedHeaders();
        // Accept either 'transmission' or 'transmission_product_id' as valid
        if (!isset($data['transmission']) && isset($data['transmission_product_id'])) {
            $data['transmission'] = $data['transmission_product_id'];
        }
        // Fill any missing expected fields with null
        foreach ($expected as $field) {
            if (!array_key_exists($field, $data)) {
                $data[$field] = null;
            }
        }
        // Optionally log extra fields, but do not block import
        $actual = array_keys($data);
        $extra = array_diff($actual, $expected);
        if (!empty($extra)) {
            Log::info('ClientsImport: Extra fields in import', [
                'extra' => $extra,
                'row_index' => $index
            ]);
        }
        return $data;
    }

    /**
     * Map a row from the import file to a Client model.
     */
    public function model(array $row)
    {
        // Log entry to confirm model() is called
        Log::info('ClientsImport: model() called', ['row' => $row]);

        // Map transmission_product_id to transmission if present
        if (isset($row['transmission_product_id']) && !isset($row['transmission'])) {
            $row['transmission'] = $row['transmission_product_id'];
        }

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

        // Process capacity - clean format like "H-50M" to extract numeric value
        $capacity = null;
        if (!empty($row['capacity'])) {
            // Extract numeric value from formats like "H-50M", "50M", "H-100M", etc.
            if (preg_match('/(\d+)/', $row['capacity'], $matches)) {
                $capacity = $matches[1];
            } else {
                $capacity = $row['capacity'];
            }
        }

        // Process auth_date - handle Excel serial dates and format dates (capture time)
        $installationDate = null;
        if (!empty($row['auth_date'])) {
            if (is_numeric($row['auth_date'])) {
                // Convert Excel serial date to PHP DateTime (with time)
                $unixTimestamp = ($row['auth_date'] - 25569) * 86400;
                $installationDate = date('Y-m-d H:i:s', $unixTimestamp);
            } else {
                // Try with double space (Excel export)
                $dateTime = \DateTime::createFromFormat('d/m/Y  H:i:s', $row['auth_date']);
                if ($dateTime) {
                    $installationDate = $dateTime->format('Y-m-d H:i:s');
                } else {
                    // Try without double space
                    $dateTime = \DateTime::createFromFormat('d/m/Y H:i:s', $row['auth_date']);
                    if ($dateTime) {
                        $installationDate = $dateTime->format('Y-m-d H:i:s');
                    } else {
                        // Try Y-m-d H:i:s directly
                        $dateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $row['auth_date']);
                        if ($dateTime) {
                            $installationDate = $dateTime->format('Y-m-d H:i:s');
                        } else {
                            $installationDate = $row['auth_date'];
                        }
                    }
                }
            }
        }

        // Check if client exists by customer_name (unique) or create new
        $client = Client::firstOrCreate(
            ['customer_name' => $row['customer_name']],
            [
                'category' => $this->customerType,
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

        // Log client creation
        Log::info('ClientsImport: Client created or found', ['client_id' => $client->id, 'customer_name' => $client->customer_name]);


        // Create or update ClientService by unique username
        $serviceData = [
            'client_id' => $client->id,
            'vendor_id' => $this->vendorId,
            'project_id' => $this->projectId,
            'username' => $row['username'] ?? null,
            'serial_number' => $row['serial_number'] ?? null,
            'capacity' => $capacity,
            'capacity_type' => $row['capacity_type'] ?? null,
            'vlan' => $vlan,
            'nrc' => $row['nrc'] ?? null,
            'mrc' => $row['mrc'] ?? null,
            'installation_date' => $installationDate,
            'status' => $row['status'] ?? 'active',
            'transmission' => $row['transmission'] ?? null,
        ];
        if (isset($row['product_id'])) {
            $serviceData['product_id'] = $row['product_id'];
        }
        if (isset($row['product_name'])) {
            $serviceData['product_name'] = $row['product_name'];
        }

        if (!empty($row['username'])) {
            $clientService = ClientService::where('username', $row['username'])->first();
            if ($clientService) {
                $clientService->fill($serviceData);
                $clientService->save();
                Log::info('ClientsImport: ClientService updated by username', ['client_service_id' => $clientService->id, 'client_id' => $client->id]);
            } else {
                $clientService = ClientService::create($serviceData);
                Log::info('ClientsImport: ClientService created', ['client_service_id' => $clientService->id, 'client_id' => $client->id]);
            }
        } else {
            $clientService = ClientService::create($serviceData);
            Log::info('ClientsImport: ClientService created (no username)', ['client_service_id' => $clientService->id, 'client_id' => $client->id]);
        }

        // Increment imported count
        $this->importedCount++;

        return $client;
    }
}
