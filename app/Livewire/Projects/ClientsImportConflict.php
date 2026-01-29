<?php
namespace App\Livewire\Projects;

class ClientsImportConflict
{
    public $rowIndex;
    public $customerName;
    public $existingData;
    public $importData;
    public $differences;

    public function __construct($rowIndex, $customerName, $existingData, $importData, $differences)
    {
        $this->rowIndex = $rowIndex;
        $this->customerName = $customerName;
        $this->existingData = $existingData;
        $this->importData = $importData;
        $this->differences = $differences;
    }
}
