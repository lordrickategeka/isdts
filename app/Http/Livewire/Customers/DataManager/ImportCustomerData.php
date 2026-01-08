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

class ImportCustomerData extends Component
{
    use WithFileUploads;

    public $importFile;

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

    public function importClients()
    {
        $this->validate();

        try {
            $path = $this->importFile->store('imports');

            // Placeholder: actual parsing/import logic should be added here.
            session()->flash('message', 'File uploaded to: ' . $path);

            $this->resetFile();
        } catch (\Exception $e) {
            $this->addError('import', 'Import failed: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.customers.data-manager.import-customer-data');
    }
}
