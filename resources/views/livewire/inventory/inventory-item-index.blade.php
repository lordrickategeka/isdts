<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="w-full mx-0 px-2 md:px-6">
            <div class="flex items-center justify-between py-4">
                <div>
                    <h1 class="text-2xl font-bold text-black">Inventory Items</h1>
                    <p class="text-sm text-gray-500">Manage inventory items, stock levels, and tracking</p>
                </div>
                @can('create_inventory')
                    <button wire:click="openModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition duration-200 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add Item
                    </button>
                @endcan
            </div>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="mx-6 mt-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mx-6 mt-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Filters -->
    <div class="mx-6 mt-6">
        <div class="bg-white rounded-lg shadow-md p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" wire:model.live.debounce.300ms="search" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="SKU, name, barcode...">
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select wire:model.live="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="product">Product</option>
                        <option value="material">Material</option>
                        <option value="equipment">Equipment</option>
                        <option value="consumable">Consumable</option>
                        <option value="spare_part">Spare Part</option>
                        <option value="other">Other</option>
                    </select>
                </div>

                <!-- Category Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select wire:model.live="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Stock Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Status</label>
                    <select wire:model.live="stock_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All Stock</option>
                        <option value="in_stock">In Stock</option>
                        <option value="low_stock">Low Stock</option>
                        <option value="out_of_stock">Out of Stock</option>
                    </select>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select wire:model.live="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Items Table -->
    <div class="mx-6 mt-6 mb-6">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU/Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">On Hand</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Reserved</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Available</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($items as $item)
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->name }}</div>
                                            <div class="text-xs text-gray-500">SKU: {{ $item->sku }}</div>
                                            @if($item->barcode)
                                                <div class="text-xs text-gray-400">Barcode: {{ $item->barcode }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ ucfirst(str_replace('_', ' ', $item->type)) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $item->category ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($item->quantity_on_hand, 2) }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->unit_of_measure }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">{{ number_format($item->quantity_reserved, 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm font-medium
                                        @if($item->quantity_available <= 0) text-red-600
                                        @elseif($item->needsReorder()) text-orange-600
                                        @else text-green-600
                                        @endif">
                                        {{ number_format($item->quantity_available, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="text-sm text-gray-900">${{ number_format($item->unit_cost, 2) }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->costing_method }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($item->is_active)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                    @endif
                                    @if($item->needsReorder())
                                        <div class="mt-1">
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">Low Stock</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end gap-2">
                                        <button wire:click="viewDetails({{ $item->id }})" class="text-blue-600 hover:text-blue-900" title="View Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        @can('edit_inventory')
                                            <button wire:click="openModal({{ $item->id }})" class="text-indigo-600 hover:text-indigo-900" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="toggleStatus({{ $item->id }})" class="text-gray-600 hover:text-gray-900" title="Toggle Status">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                </svg>
                                            </button>
                                        @endcan
                                        @can('delete_inventory')
                                            <button wire:click="confirmDelete({{ $item->id }})" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 mb-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        <p class="text-lg font-medium mb-2">No inventory items found</p>
                                        <p class="text-sm">Try adjusting your filters or add a new item</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($items->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $items->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeModal">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">{{ $itemId ? 'Edit Inventory Item' : 'Add New Inventory Item' }}</h3>
                    <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <!-- SKU -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="sku" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Barcode -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Barcode</label>
                            <input type="text" wire:model="barcode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('barcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Item Name <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                            @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                            <select wire:model="item_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                                <option value="">Select Type</option>
                                <option value="product">Product</option>
                                <option value="material">Material</option>
                                <option value="equipment">Equipment</option>
                                <option value="consumable">Consumable</option>
                                <option value="spare_part">Spare Part</option>
                                <option value="other">Other</option>
                            </select>
                            @error('item_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <input type="text" wire:model="item_category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Unit of Measure -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit of Measure <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="unit_of_measure" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="units, kg, meters, liters" required>
                        </div>

                        <!-- Costing Method -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Costing Method</label>
                            <select wire:model="costing_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="FIFO">FIFO</option>
                                <option value="LIFO">LIFO</option>
                                <option value="Average">Average</option>
                                <option value="Standard">Standard</option>
                            </select>
                        </div>

                        <!-- Unit Cost -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Cost <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" wire:model="unit_cost" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" required>
                        </div>

                        <!-- Reorder Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Level</label>
                            <input type="number" step="0.01" wire:model="reorder_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Reorder Quantity -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reorder Quantity</label>
                            <input type="number" step="0.01" wire:model="reorder_quantity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Max Stock Level -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Max Stock Level</label>
                            <input type="number" step="0.01" wire:model="max_stock_level" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Product -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Linked Product</label>
                            <select wire:model="product_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">None</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Preferred Vendor -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preferred Vendor</label>
                            <select wire:model="preferred_vendor_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">None</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tracking Options -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Options</label>
                            <div class="flex gap-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="track_serial_numbers" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Track Serial Numbers</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="track_batches" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Track Batches</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="track_expiry" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Track Expiry</span>
                                </label>
                            </div>
                        </div>

                        <!-- Status Options -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Item Status</label>
                            <div class="flex gap-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="is_active" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="is_stockable" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Stockable</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="is_purchasable" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Purchasable</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="checkbox" wire:model="is_sellable" class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-700">Sellable</span>
                                </label>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea wire:model="notes" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" wire:click="closeModal" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200">
                            {{ $itemId ? 'Update' : 'Create' }} Item
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($itemId && !$showModal && !$showDetailsModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-16 h-16 text-red-600 mx-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-2">Delete Inventory Item?</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            Are you sure you want to delete this inventory item? This action cannot be undone.
                        </p>
                    </div>
                    <div class="flex gap-3 justify-center px-4 py-3">
                        <button wire:click="$set('itemId', null)" class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg transition duration-200">
                            Cancel
                        </button>
                        <button wire:click="delete" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition duration-200">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Details Modal -->
    @if($showDetailsModal && $selectedItem)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeDetailsModal">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white" wire:click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">Inventory Item Details</h3>
                    <button wire:click="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <!-- Basic Info -->
                    <div class="col-span-2 bg-gray-50 p-4 rounded-lg">
                        <h4 class="font-semibold text-gray-900 mb-2">{{ $selectedItem->name }}</h4>
                        <p class="text-sm text-gray-600">{{ $selectedItem->description }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">SKU</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedItem->sku }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <p class="mt-1 text-sm text-gray-900">{{ ucfirst(str_replace('_', ' ', $selectedItem->type)) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedItem->category ?? '-' }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit of Measure</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedItem->unit_of_measure }}</p>
                    </div>

                    <!-- Stock Info -->
                    <div class="col-span-2 border-t pt-4">
                        <h4 class="font-semibold text-gray-900 mb-3">Stock Levels</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-3 rounded">
                                <p class="text-xs text-gray-600">On Hand</p>
                                <p class="text-lg font-semibold text-blue-700">{{ number_format($selectedItem->quantity_on_hand, 2) }}</p>
                            </div>
                            <div class="bg-orange-50 p-3 rounded">
                                <p class="text-xs text-gray-600">Reserved</p>
                                <p class="text-lg font-semibold text-orange-700">{{ number_format($selectedItem->quantity_reserved, 2) }}</p>
                            </div>
                            <div class="bg-green-50 p-3 rounded">
                                <p class="text-xs text-gray-600">Available</p>
                                <p class="text-lg font-semibold text-green-700">{{ number_format($selectedItem->quantity_available, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Costing Info -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Costing Method</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $selectedItem->costing_method }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Unit Cost</label>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($selectedItem->unit_cost, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Average Cost</label>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($selectedItem->average_cost, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Value</label>
                        <p class="mt-1 text-sm text-gray-900">${{ number_format($selectedItem->total_value, 2) }}</p>
                    </div>

                    <!-- Stock Levels per Location -->
                    @if($selectedItem->locationStock->count() > 0)
                        <div class="col-span-2 border-t pt-4">
                            <h4 class="font-semibold text-gray-900 mb-3">Stock by Location</h4>
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left">Location</th>
                                        <th class="px-3 py-2 text-right">On Hand</th>
                                        <th class="px-3 py-2 text-right">Reserved</th>
                                        <th class="px-3 py-2 text-right">Available</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($selectedItem->locationStock as $locationStock)
                                        <tr>
                                            <td class="px-3 py-2">{{ $locationStock->location->name }}</td>
                                            <td class="px-3 py-2 text-right">{{ number_format($locationStock->quantity_on_hand, 2) }}</td>
                                            <td class="px-3 py-2 text-right">{{ number_format($locationStock->quantity_reserved, 2) }}</td>
                                            <td class="px-3 py-2 text-right">{{ number_format($locationStock->quantity_available, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
