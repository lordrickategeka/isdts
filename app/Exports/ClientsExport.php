<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ClientsExport implements FromArray, WithHeadings, WithStyles
{
    protected $headers;
    protected $data;

    public function __construct(array $headers, array $data)
    {
        $this->headers = $headers;
        $this->data = $data;
    }

    public function array(): array
    {
        return $this->data;
    }

    public function headings(): array
    {
        return $this->headers;
    }

    public function styles(Worksheet $sheet)
    {
        // Style the header row
        $lastColumn = chr(64 + count($this->headers)); // Convert to letter (A, B, C, etc.)

        $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
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

        // Auto-size columns
        foreach (range('A', $lastColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        return [];
    }
}
