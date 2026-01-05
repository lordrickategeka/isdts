<?php

namespace App\Services;

use App\Models\ServiceFeasibilityVendor;
use App\Models\VendorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ServiceFeasibilityVendorService
{
    /**
     * Ensure the given vendor service belongs to the given vendor.
     * Throws ValidationException when invalid.
     *
     * @param int|null $vendorServiceId
     * @param int $vendorId
     * @return void
     * @throws ValidationException
     */
    public function ensureServiceBelongsToVendor($vendorServiceId, $vendorId)
    {
        if (empty($vendorServiceId)) {
            return;
        }

        $exists = VendorService::where('id', $vendorServiceId)
            ->where('vendor_id', $vendorId)
            ->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'selectedVendorService' => 'Selected service does not belong to the selected vendor.'
            ]);
        }
    }

    /**
     * Ensure the vendor has not already been added to the feasibility.
     * Throws ValidationException when duplicate found.
     *
     * @param int $feasibilityId
     * @param int $vendorId
     * @param int|null $ignoreEntryId
     * @return void
     * @throws ValidationException
     */
    public function ensureUniqueVendorForFeasibility($feasibilityId, $vendorId, $ignoreEntryId = null)
    {
        $query = ServiceFeasibilityVendor::where('service_feasibility_id', $feasibilityId)
            ->where('vendor_id', $vendorId);

        if ($ignoreEntryId) {
            $query->where('id', '!=', $ignoreEntryId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'selectedVendor' => 'This vendor has already been added.'
            ]);
        }
    }

    /**
     * Create a ServiceFeasibilityVendor entry inside a transaction.
     *
     * @param array $data
     * @return ServiceFeasibilityVendor
     */
    public function createVendorEntry(array $data)
    {
        return DB::transaction(function () use ($data) {
            return ServiceFeasibilityVendor::create($data);
        });
    }

    /**
     * Update a ServiceFeasibilityVendor entry inside a transaction.
     *
     * @param int $entryId
     * @param array $data
     * @return ServiceFeasibilityVendor
     */
    public function updateVendorEntry($entryId, array $data)
    {
        return DB::transaction(function () use ($entryId, $data) {
            $entry = ServiceFeasibilityVendor::findOrFail($entryId);
            $entry->update($data);
            return $entry;
        });
    }
}
