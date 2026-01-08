<?php

namespace App\Exports;

use App\Models\Region;
use App\Models\District;
use App\Models\Vendor;
use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientImportTemplateExport implements FromArray, WithHeadings, WithStyles, WithColumnWidths
{
    public function array(): array
    {
        // Empty rows for the template data entry area
        return [
            [], // Empty row for user to fill
        ];
    }

    public function headings(): array
    {
        return [
            'Customer Name',
            'Phone',
            'Email',
            'Address',
            'Latitude',
            'Longitude',
            'Region',
            'District',
            'Installation Engineer',
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
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row (19 columns: A-S)
        $sheet->getStyle('A1:S1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF'],
                'bold' => true,
            ],
        ]);

        // Add reference data starting from row 4
        $currentRow = 4;

        // Add reference data section header
        $sheet->setCellValue('A' . $currentRow, 'REFERENCE DATA - Use these values in your import');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true)->setSize(12);
        $sheet->mergeCells('A' . $currentRow . ':S' . $currentRow);
        $currentRow += 2; 2;

        // Regions
        $regions = Region::where('is_active', true)->get();
        $sheet->setCellValue('A' . $currentRow, 'Regions:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;
        foreach ($regions as $region) {
            $sheet->setCellValue('A' . $currentRow, $region->name);
            $currentRow++;
        }
        $currentRow++;

        // Districts by Region
        $sheet->setCellValue('A' . $currentRow, 'Districts by Region:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;
        foreach ($regions as $region) {
            $districts = District::where('region_id', $region->id)
                ->where('is_active', true)
                ->pluck('name')
                ->toArray();
            $sheet->setCellValue('A' . $currentRow, $region->name . ':');
            $col = 'B';
            foreach ($districts as $district) {
                $sheet->setCellValue($col . $currentRow, $district);
                $col++;
            }
            $currentRow++;
        }
        $currentRow++;

        // Products (Transmission)sion)
        $sheet->setCellValue('A' . $currentRow, 'Transmission Products (Product ID - Product Name):');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;

        $products = Product::all();
        foreach ($products as $product) {
            $sheet->setCellValue('A' . $currentRow, $product->id);
            $sheet->setCellValue('B' . $currentRow, $product->name);
            $currentRow++;
        }
        $currentRow++;

        // Capacity Types
        $sheet->setCellValue('A' . $currentRow, 'Capacity Types:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'Shared');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'Dedicated');
        $currentRow += 2;

        // Administrative Status
        $sheet->setCellValue('A' . $currentRow, 'Administrative Status Options:');
        $sheet->getStyle('A' . $currentRow)->getFont()->setBold(true);
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'Enabled');
        $currentRow++;
        $sheet->setCellValue('A' . $currentRow, 'Disabled');

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,  // Customer Name
            'B' => 15,  // Phone
            'C' => 25,  // Email
            'D' => 30,  // Address
            'E' => 12,  // Latitude
            'F' => 12,  // Longitude
            'G' => 15,  // Region
            'H' => 15,  // District
            'I' => 20,  // Installation Engineer
            'J' => 25,  // Transmission (Product ID)
            'K' => 15,  // Username
            'L' => 15,  // Serial Number
            'M' => 12,  // Capacity
            'N' => 15,  // Capacity Type
            'O' => 10,  // VLAN
            'P' => 12,  // NRC
            'Q' => 12,  // MRC
            'R' => 15,  // Auth Date
            'S' => 20,  // Administrative Status
        ];
    }
}
