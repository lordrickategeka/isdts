<?php

namespace App\Http\Livewire\Customers\DataManager;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Region;
use App\Models\District;
use App\Models\Vendor;
use App\Models\Product;
use App\Imports\ClientsImport;
use App\Imports\ClientsImportConflict;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ImportCustomerData extends Component
{
    use WithFileUploads;

    public $importFile;

    public $conflicts = [];
    public $conflictActions = [];
    /**
     * Dry-run import to detect conflicts without saving.
     */
    public function dryRunImport()
    {
        $this->validate();
        $conflicts = [];
        try {
            $path = $this->importFile->store('imports');
            $rows = Excel::toArray(new ClientsImport($this->projectId ?? null, $this->vendorId ?? null, $this->customerType ?? null), $path)[0];
            $expected = ClientsImport::expectedHeaders();
            foreach ($rows as $i => $row) {
                // Normalize keys
                $rowNorm = [];
                foreach ($row as $k => $v) {
                    $rowNorm[ClientsImport::normalizeHeaderStatic($k)] = $v;
                }
                $customerName = $rowNorm['customer_name'] ?? null;
                if (!$customerName) continue;
                $existing = \App\Models\Client::where('customer_name', $customerName)->first();
                if ($existing) {
                    $diffs = [];
                    foreach ($expected as $field) {
                        $importVal = $rowNorm[$field] ?? null;
                        $existingVal = $existing->$field ?? null;
                        if ($importVal != $existingVal) {
                            $diffs[$field] = [
                                'existing' => $existingVal,
                                'import' => $importVal
                            ];
                        }
                    }
                    if (!empty($diffs)) {
                        $conflicts[] = new ClientsImportConflict($i + 2, $customerName, $existing->toArray(), $rowNorm, $diffs); // +2 for header and 0-index
                    }
                }
            }
            $this->conflicts = $conflicts;
        } catch (\Exception $e) {
            $this->addError('import', 'Dry-run failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle user choices for conflicts and perform import.
     */
    public function resolveConflicts()
    {
        try {
            $path = $this->importFile->store('imports');
            $rows = Excel::toArray(new ClientsImport($this->projectId ?? null, $this->vendorId ?? null, $this->customerType ?? null), $path)[0];
            $expected = ClientsImport::expectedHeaders();
            $importedCount = 0;
            foreach ($rows as $i => $row) {
                $rowNorm = [];
                foreach ($row as $k => $v) {
                    $rowNorm[ClientsImport::normalizeHeaderStatic($k)] = $v;
                }
                $customerName = $rowNorm['customer_name'] ?? null;
                if (!$customerName) continue;
                $existing = \App\Models\Client::where('customer_name', $customerName)->first();
                if ($existing) {
                    $update = false;
                    foreach ($expected as $field) {
                        $action = $this->conflictActions[$i + 2][$field] ?? 'skip';
                        if ($action === 'update') {
                            $existing->$field = $rowNorm[$field] ?? null;
                            $update = true;
                        }
                    }
                    if ($update) {
                        $existing->save();
                        $importedCount++;
                    }
                } else {
                    // New client, create as usual
                    $client = \App\Models\Client::create([
                        'customer_name' => $rowNorm['customer_name'],
                        'category' => $this->customerType,
                        'phone' => $rowNorm['phone'] ?? null,
                        'email' => $rowNorm['email'] ?? null,
                        'address' => $rowNorm['address'] ?? null,
                        'latitude' => $rowNorm['latitude'] ?? null,
                        'longitude' => $rowNorm['longitude'] ?? null,
                        'region' => $rowNorm['region'] ?? null,
                        'district' => $rowNorm['district'] ?? null,
                        'contact_person' => $rowNorm['installation_engineer'] ?? null,
                        'created_by' => Auth::user()->id,
                    ]);
                    $importedCount++;
                }
            }
            session()->flash('success', "$importedCount clients imported/updated successfully.");
            $this->resetFile();
            $this->conflicts = [];
        } catch (\Exception $e) {
            $this->addError('import', 'Import failed: ' . $e->getMessage());
        }
    }

    protected $rules = [
        'importFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
    ];

    public function resetFile()
    {
        $this->importFile = null;
        $this->resetValidation('importFile');
    }

    public function downloadTemplate()
    {
        try {
            $filename = 'client_import_template_' . date('Y-m-d_His') . '.csv';
            $filepath = storage_path('app/exports/' . $filename);

            // Create exports directory if it doesn't exist
            if (!file_exists(storage_path('app/exports'))) {
                mkdir(storage_path('app/exports'), 0755, true);
            }

            $file = fopen($filepath, 'w');

            // Write header
            fputcsv($file, [
                'Customer Name',
                'Customer Type',
                'Phone',
                'Email',
                'Address',
                'Latitude',
                'Longitude',
                'Region',
                'District',
                'Installation Engineer',
                'Vendor ID',
                'Transmission (Product ID)',
                'Username',
                'Serial Number',
                'Capacity',
                'Capacity Type',
                'VLAN',
                'NRC',
                'MRC',
                'Auth Date',
                'Administrative Status'
            ]);

            // Add reference data section
            fputcsv($file, []); // Empty row
            fputcsv($file, ['REFERENCE DATA - Use these values in your import']);
            fputcsv($file, []);

            // Customer Types
            fputcsv($file, ['Customer Types:']);
            fputcsv($file, ['Home', 'Corporate']);
            fputcsv($file, []);

            // Regions
            $regions = Region::where('is_active', true)->get();
            fputcsv($file, ['Regions:']);
            foreach ($regions as $region) {
                fputcsv($file, [$region->name]);
            }
            fputcsv($file, []);

            // Districts by Region
            fputcsv($file, ['Districts by Region:']);
            foreach ($regions as $region) {
                $districts = District::where('region_id', $region->id)
                    ->where('is_active', true)
                    ->pluck('name')
                    ->toArray();
                fputcsv($file, array_merge([$region->name . ':'], $districts));
            }
            fputcsv($file, []);

            // Vendors
            $vendors = Vendor::where('status', 'active')->get();
            fputcsv($file, ['Vendors (ID - Name):']);
            foreach ($vendors as $vendor) {
                fputcsv($file, [$vendor->id, $vendor->name]);
            }
            fputcsv($file, []);

            // Products by Vendor
            fputcsv($file, ['Transmission Products by Vendor (Product ID - Product Name - Vendor):']);
            foreach ($vendors as $vendor) {
                $products = Product::whereHas('vendorService', function ($q) use ($vendor) {
                    $q->where('vendor_id', $vendor->id);
                })->get();

                foreach ($products as $product) {
                    fputcsv($file, [$product->id, $product->name, $vendor->name]);
                }
            }
            fputcsv($file, []);

            // Capacity Types
            fputcsv($file, ['Capacity Types:']);
            fputcsv($file, ['Shared', 'Dedicated']);
            fputcsv($file, []);

            // Administrative Status options
            fputcsv($file, ['Administrative Status Options:']);
            fputcsv($file, ['Enabled', 'Disabled']);

            fclose($file);

            session()->flash('success', 'Template downloaded successfully with reference data');

            return response()->download($filepath)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error('Template download failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to download template: ' . $e->getMessage());
        }
    }

    public function importClients()
    {
        $this->dryRunImport();
        if (!empty($this->conflicts)) {
            // Show conflict UI, do not import yet
            return;
        }
        // No conflicts, proceed with normal import
        try {
            $path = $this->importFile->store('imports');
            $import = new ClientsImport($this->projectId ?? null, $this->vendorId ?? null, $this->customerType ?? null);
            Excel::import($import, $path);
            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            if ($importedCount > 0) {
                session()->flash('success', "$importedCount clients imported successfully.");
            } elseif (!empty($errors)) {
                session()->flash('error', 'No clients imported. Errors: ' . implode('; ', $errors));
            } else {
                session()->flash('error', 'No clients were imported.');
            }
            $this->resetFile();
        } catch (\Exception $e) {
            $this->addError('import', 'Import failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        if (!empty($this->conflicts)) {
            return view('livewire.customers.data-manager.import-conflicts', ['conflicts' => $this->conflicts]);
        }
        return view('livewire.customers.data-manager.import-customer-data');
    }
    // Static helper for header normalization
    public static function normalizeHeaderStatic($header)
    {
        $header = strtolower($header);
        $header = preg_replace('/[^a-z0-9]+/', '_', $header);
        $header = trim($header, '_');
        return $header;
    }
}
