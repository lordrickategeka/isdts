<div x-data="{ tab: 'details' }">
    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded mx-6 mt-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs -->
    <div class="flex gap-2 border-b border-gray-200 px-6 sticky top-0 bg-white z-10">
        <button @click="tab = 'details'"
                :class="tab === 'details' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-3 font-medium text-sm hover:text-blue-600 transition-colors">
            Feasibility Details
        </button>
        <button @click="tab = 'vendors'"
                :class="tab === 'vendors' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-3 font-medium text-sm hover:text-blue-600 transition-colors">
            Vendors & Costs
        </button>
        <button @click="tab = 'materials'"
                :class="tab === 'materials' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-3 font-medium text-sm hover:text-blue-600 transition-colors">
            Materials
        </button>
        <button @click="tab = 'summary'"
                :class="tab === 'summary' ? 'border-b-2 border-blue-600 text-blue-600' : 'text-gray-500'"
                class="px-4 py-3 font-medium text-sm hover:text-blue-600 transition-colors">
            Cost Summary
        </button>
    </div>

    <!-- Tab Content -->
    <div class="p-6">
        <!-- Feasibility Details Tab -->
        <div x-show="tab === 'details'" x-transition>
            <div class="space-y-4">
                <!-- Is Feasible -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Is the area feasible? <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model="is_feasible" value="1"
                                   class="form-radio text-blue-600">
                            <span class="ml-2">Yes</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="radio" wire:model="is_feasible" value="0"
                                   class="form-radio text-blue-600">
                            <span class="ml-2">No</span>
                        </label>
                    </div>
                    @error('is_feasible')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Notes
                    </label>
                    <textarea wire:model="notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Add any additional notes about the feasibility..."></textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Save Button -->
                <div>
                    <button wire:click="saveFeasibility"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                        Save Feasibility Details
                    </button>
                </div>
            </div>
        </div>

        <!-- Vendors & Costs Tab -->
        <div x-show="tab === 'vendors'" x-transition>
            <div class="bg-white rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Vendor Details & Costs</h2>
                    <button wire:click="openVendorModal"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Vendor
                    </button>
                </div>

                @if(count($vendors) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product/Service</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">NRC Cost</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">MRC Cost</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($vendors as $vendor)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $vendor['vendor']['name'] ?? 'N/A' }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $vendor['vendor_service']['service_name'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-right">UGX {{ number_format($vendor['nrc_cost'], 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-right">UGX {{ number_format($vendor['mrc_cost'], 2) }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $vendor['notes'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            <button wire:click="editVendor({{ $vendor['id'] }})"
                                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                                Edit
                                            </button>
                                            <button wire:click="deleteVendor({{ $vendor['id'] }})"
                                                    onclick="return confirm('Are you sure?')"
                                                    class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-semibold">
                                <tr>
                                    <td colspan="2" class="px-4 py-3 text-sm text-right">Total:</td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        UGX {{ number_format(collect($vendors)->sum('nrc_cost'), 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        UGX {{ number_format(collect($vendors)->sum('mrc_cost'), 2) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No vendors added yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Click the "Add Vendor" button above to get started.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Materials Tab -->
        <div x-show="tab === 'materials'" x-transition>
            <div class="bg-white rounded-lg">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Materials</h2>
                    <button wire:click="openMaterialModal"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add Material
                    </button>
                </div>

                @if(count($materials) > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-200 rounded-lg">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total Cost</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                    <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach($materials as $material)
                                    <tr>
                                        <td class="px-4 py-3 text-sm">{{ $material['name'] }}</td>
                                        <td class="px-4 py-3 text-sm text-right">{{ $material['quantity'] }}</td>
                                        <td class="px-4 py-3 text-sm text-right">UGX {{ number_format($material['unit_cost'], 2) }}</td>
                                        <td class="px-4 py-3 text-sm text-right">UGX {{ number_format($material['total_cost'], 2) }}</td>
                                        <td class="px-4 py-3 text-sm">{{ $material['description'] ?? '-' }}</td>
                                        <td class="px-4 py-3 text-sm text-center">
                                            <button wire:click="editMaterial({{ $material['id'] }})"
                                                    class="text-blue-600 hover:text-blue-800 mr-2">
                                                Edit
                                            </button>
                                            <button wire:click="deleteMaterial({{ $material['id'] }})"
                                                    onclick="return confirm('Are you sure?')"
                                                    class="text-red-600 hover:text-red-800">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50 font-semibold">
                                <tr>
                                    <td colspan="3" class="px-4 py-3 text-sm text-right">Total:</td>
                                    <td class="px-4 py-3 text-sm text-right">
                                        UGX {{ number_format(collect($materials)->sum('total_cost'), 2) }}
                                    </td>
                                    <td colspan="2"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No materials added yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Click the "Add Material" button above to get started.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Cost Summary Tab -->
        <div x-show="tab === 'summary'" x-transition>
            @if($feasibility)
                <div class="space-y-6">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4">
                            <p class="text-sm text-blue-700 font-medium mb-1">Total NRC</p>
                            <p class="text-2xl font-bold text-blue-900">
                                UGX {{ number_format(collect($vendors)->sum('nrc_cost'), 0) }}
                            </p>
                            <p class="text-xs text-blue-600 mt-1">Non-Recurring Cost</p>
                        </div>
                        <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-lg p-4">
                            <p class="text-sm text-green-700 font-medium mb-1">Total MRC</p>
                            <p class="text-2xl font-bold text-green-900">
                                UGX {{ number_format(collect($vendors)->sum('mrc_cost'), 0) }}
                            </p>
                            <p class="text-xs text-green-600 mt-1">Monthly Recurring Cost</p>
                        </div>
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4">
                            <p class="text-sm text-purple-700 font-medium mb-1">Total Materials</p>
                            <p class="text-2xl font-bold text-purple-900">
                                UGX {{ number_format(collect($materials)->sum('total_cost'), 0) }}
                            </p>
                            <p class="text-xs text-purple-600 mt-1">{{ count($materials) }} item(s)</p>
                        </div>
                        <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4">
                            <p class="text-sm text-orange-700 font-medium mb-1">Grand Total</p>
                            <p class="text-2xl font-bold text-orange-900">
                                UGX {{ number_format(collect($vendors)->sum('nrc_cost') + collect($materials)->sum('total_cost'), 0) }}
                            </p>
                            <p class="text-xs text-orange-600 mt-1">NRC + Materials</p>
                        </div>
                    </div>

                    <!-- Detailed Breakdown -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Vendors Breakdown -->
                        @if(count($vendors) > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Vendors Breakdown</h4>
                                <div class="space-y-2">
                                    @foreach($vendors as $vendor)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $vendor['vendor']['name'] ?? 'N/A' }}</p>
                                                <p class="text-xs text-gray-500">{{ $vendor['vendor_service']['service_name'] ?? '-' }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">UGX {{ number_format($vendor['nrc_cost'], 0) }}</p>
                                                <p class="text-xs text-gray-500">MRC: {{ number_format($vendor['mrc_cost'], 0) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Materials Breakdown -->
                        @if(count($materials) > 0)
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h4 class="font-semibold text-gray-900 mb-3">Materials Breakdown</h4>
                                <div class="space-y-2">
                                    @foreach($materials as $material)
                                        <div class="flex justify-between items-center py-2 border-b border-gray-100 last:border-0">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">{{ $material['name'] }}</p>
                                                <p class="text-xs text-gray-500">Qty: {{ $material['quantity'] }} @ UGX {{ number_format($material['unit_cost'], 0) }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm font-medium text-gray-900">UGX {{ number_format($material['total_cost'], 0) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Feasibility Status -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                        <h4 class="font-semibold text-gray-900 mb-2">Feasibility Status</h4>
                        <div class="flex items-center gap-4">
                            <span class="text-sm text-gray-600">Area is feasible:</span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $is_feasible ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $is_feasible ? 'Yes' : 'No' }}
                            </span>
                        </div>
                        @if($notes)
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 font-medium">Notes:</p>
                                <p class="text-sm text-gray-700 mt-1">{{ $notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No feasibility data yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Start by filling out the Feasibility Details tab.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Vendor Modal -->
    @if($showVendorModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeVendorModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $editingVendorId ? 'Edit Vendor' : 'Add Vendor' }}
                        </h3>
                        <button wire:click="closeVendorModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Select Vendor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Vendor <span class="text-red-500">*</span>
                            </label>
                            <select wire:model="selectedVendor"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Vendor</option>
                                @foreach($allVendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @error('selectedVendor')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Select Vendor Service -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Vendor Product/Service
                            </label>
                            <select wire:model="selectedVendorService"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    {{ !$selectedVendor ? 'disabled' : '' }}>
                                <option value="">Select Product/Service</option>
                                @foreach($vendorServices as $vs)
                                    <option value="{{ $vs->id }}">{{ $vs->service_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- NRC Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                NRC Cost (Non-Recurring Cost) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="nrc_cost" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            @error('nrc_cost')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- MRC Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                MRC Cost (Monthly Recurring Cost) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="mrc_cost" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            @error('mrc_cost')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Notes
                            </label>
                            <textarea wire:model="vendor_notes" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Additional notes..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2">
                            <button wire:click="closeVendorModal"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Cancel
                            </button>
                            <button wire:click="{{ $editingVendorId ? 'updateVendor' : 'addVendor' }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                {{ $editingVendorId ? 'Update' : 'Add' }} Vendor
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Material Modal -->
    @if($showMaterialModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4">
                <div class="fixed inset-0 bg-black opacity-50" wire:click="closeMaterialModal"></div>

                <div class="relative bg-white rounded-lg shadow-xl max-w-2xl w-full p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ $editingMaterialId ? 'Edit Material' : 'Add Material' }}
                        </h3>
                        <button wire:click="closeMaterialModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <!-- Material Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Material Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" wire:model="material_name"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="e.g., Fiber Optic Cable">
                            @error('material_name')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Quantity <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="material_quantity" min="1"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="1">
                            @error('material_quantity')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Unit Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Unit Cost <span class="text-red-500">*</span>
                            </label>
                            <input type="number" wire:model="material_unit_cost" step="0.01" min="0"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                   placeholder="0.00">
                            @error('material_unit_cost')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Total Cost Display -->
                        @if($material_quantity && $material_unit_cost)
                            <div class="bg-gray-50 p-3 rounded-lg">
                                <p class="text-sm text-gray-600">Total Cost</p>
                                <p class="text-lg font-bold text-gray-900">
                                    UGX {{ number_format($material_quantity * $material_unit_cost, 2) }}
                                </p>
                            </div>
                        @endif

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Description
                            </label>
                            <textarea wire:model="material_description" rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Additional details about the material..."></textarea>
                        </div>

                        <!-- Actions -->
                        <div class="flex justify-end gap-2">
                            <button wire:click="closeMaterialModal"
                                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-medium">
                                Cancel
                            </button>
                            <button wire:click="{{ $editingMaterialId ? 'updateMaterial' : 'addMaterial' }}"
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                                {{ $editingMaterialId ? 'Update' : 'Add' }} Material
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
