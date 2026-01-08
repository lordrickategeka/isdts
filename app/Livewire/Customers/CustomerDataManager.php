<?php

namespace App\Livewire\Customers;

use App\Models\Client;
use App\Models\ClientService;
use App\Models\District;
use App\Models\Product;
use App\Models\Region;
use App\Models\Vendor;
use App\Imports\ClientsImport;
use Illuminate\Support\Facades\Log;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class CustomerDataManager extends Component
{
    use WithFileUploads;

    public $projectId;
    public $showImportModal = false;
    public $importFile;
    // when true, component should render inline import UI instead of only modal
    public $inline = false;

    /**
     * Open import modal
     */
    public function openImportModal()
    {
        $this->showImportModal = true;
        $this->importFile = null;
    }

    /**
     * Close import modal
     */
    public function closeImportModal()
    {
        $this->showImportModal = false;
        $this->importFile = null;
    }

    /**
     * Process the import file
     */
    public function importClients()
    {
        $this->validate([
            'importFile' => 'required|file|mimes:csv,txt,xlsx,xls|max:20048',
        ]);

        Log::info('ClientImport: Starting import process', [
            'project_id' => $this->projectId,
            'filename' => $this->importFile->getClientOriginalName(),
            'size' => $this->importFile->getSize(),
            'mime_type' => $this->importFile->getMimeType()
        ]);

        try {
            // Create import instance
            $import = new ClientsImport($this->projectId);

            Log::info('ClientImport: Processing file with Laravel Excel');

            // Process the import
            Excel::import($import, $this->importFile);

            // Get results
            $importedCount = $import->getImportedCount();
            $errors = $import->getErrors();
            $failures = $import->failures();

            Log::info('ClientImport: Import completed', [
                'imported_count' => $importedCount,
                'validation_failures' => count($failures),
                'processing_errors' => count($errors)
            ]);

            // Build result message
            if ($importedCount > 0) {
                $message = "Successfully imported {$importedCount} client(s)";

                if (count($failures) > 0) {
                    $message .= ". " . count($failures) . " row(s) failed validation.";

                    Log::warning('ClientImport: Validation failures summary', [
                        'total_failures' => count($failures),
                        'failed_rows' => array_map(fn($f) => $f->row(), $failures->toArray())
                    ]);
                }

                session()->flash('success', $message);

                // Show first few validation errors if any
                if (count($failures) > 0 && count($failures) <= 5) {
                    $errorMessages = [];
                    foreach ($failures as $failure) {
                        $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());
                    }
                    session()->flash('warning', implode(' | ', $errorMessages));
                }
            } else {
                Log::warning('ClientImport: No clients imported', [
                    'validation_failures' => count($failures),
                    'processing_errors' => count($errors)
                ]);
                session()->flash('warning', 'No clients were imported. Please check the file format and logs.');
            }

            // Show other errors if any
            if (count($errors) > 0) {
                Log::error('ClientImport: Processing errors occurred', [
                    'error_count' => count($errors),
                    'errors' => $errors
                ]);
            }

                // Close modal and dispatch event to parent
                $this->closeImportModal();

                $this->dispatch('clients-imported');
                // Trigger a browser reload so the user sees the updated customers list
                $this->dispatch('customers-imported-refresh');

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessages = [];

            Log::error('ClientImport: Validation exception', [
                'failure_count' => count($failures),
                'project_id' => $this->projectId
            ]);

            foreach ($failures as $failure) {
                $errorMessages[] = "Row {$failure->row()}: " . implode(', ', $failure->errors());

                Log::error('ClientImport: Validation failure detail', [
                    'row' => $failure->row(),
                    'attribute' => $failure->attribute(),
                    'errors' => $failure->errors(),
                    'values' => $failure->values()
                ]);
            }

            $this->addError('import', 'Validation failed: ' . implode(' | ', array_slice($errorMessages, 0, 3)));

        } catch (\Exception $e) {
            Log::error('ClientImport: Import failed with exception', [
                'project_id' => $this->projectId,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->addError('import', 'Failed to import clients: ' . $e->getMessage());
        }
    }

    /**
     * Download import template with dropdown options
     */
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
                $products = Product::whereHas('vendorService', function($q) use ($vendor) {
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

    /**
     * Export clients for the current project
     */
    public function exportClients()
    {
        try {
            // Get all clients for this project
            $clients = Client::whereHas('clientServices', function($q) {
                $q->where('project_id', $this->projectId);
            })->with(['clientServices' => function($q) {
                $q->where('project_id', $this->projectId)
                  ->with(['vendor', 'serviceType']);
            }])->get();

            $filename = 'project_' . $this->projectId . '_clients_' . date('Y-m-d_His') . '.csv';
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
                'Region',
                'District',
                'Latitude',
                'Longitude',
                'Installation Engineer',
                'Vendor',
                'Transmission',
                'Capacity',
                'Capacity Type',
                'Username',
                'Serial Number',
                'VLAN',
                'NRC',
                'MRC',
                'Installation Date',
                'Status'
            ]);

            // Write data rows
            foreach ($clients as $client) {
                foreach ($client->clientServices as $service) {
                    fputcsv($file, [
                        $client->customer_name,
                        $client->category ?? 'Home',
                        $client->phone,
                        $client->email,
                        $client->region,
                        $client->district,
                        $client->latitude,
                        $client->longitude,
                        $client->contact_person,

                        $service->vendor->name ?? '',
                        $service->service_type,
                        $service->capacity,
                        $service->capacity_type,
                        $service->username,
                        $service->serial_number,
                        $service->vlan,
                        $service->nrc,
                        $service->mrc,
                        $service->installation_date ? $service->installation_date->format('Y-m-d H:i') : '',
                        $service->status
                    ]);
                }
            }

            fclose($file);

            session()->flash('success', "Exported {$clients->count()} client(s) to {$filename}");

            return response()->download($filepath)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Export clients failed', ['error' => $e->getMessage()]);
            session()->flash('error', 'Failed to export clients: ' . $e->getMessage());
        }
    }

    /**
     * Clear the selected file (used by inline import UI)
     */
    public function resetFile()
    {
        $this->importFile = null;
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.customers.customer-data-manager');
    }
}
