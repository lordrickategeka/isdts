<div>
    <div class="bg-base-100 border-b border-gray-200">
        <div class="w-full mx-0 px-2 md:px-6">
            <div class="flex items-center justify-between py-4">
                <div>
                    <h1 class="text-2xl font-bold text-black">{{ $project->name }}</h1>
                    <p class="text-sm text-gray-500">{{ $project->project_code }} - Project Budget Planning</p>

                </div>
                <a href="{{ route('projects.list') }}" class="text-1xl text-gray-600 hover:text-gray-800">
                ← Back to Projects
            </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 pt-6 pb-8">
        <div class="max-w-6xl mx-auto">

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Budget Summary -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700">Total Budget</h3>
                        <p class="text-2xl font-bold text-blue-600">{{ $currencySymbol }}{{ number_format($totalBudget, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-600">Saved Items</p>
                        <p class="text-xl font-semibold text-gray-800">{{ $budgetItems->count() }}</p>
                    </div>
                    @if(count($pendingItems) > 0)
                        <div class="text-right">
                            <p class="text-xs text-orange-600 font-medium">Pending Items</p>
                            <p class="text-xl font-semibold text-orange-500">{{ count($pendingItems) }}</p>
                            <p class="text-xs text-orange-600">{{ $currencySymbol }}{{ number_format($pendingTotal, 2) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mb-4 flex justify-between items-center">
                @can('create_budget_items')
                    <button wire:click="toggleAddForm"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        <i class="fas {{ $showAddForm ? 'fa-times' : 'fa-plus' }} mr-2"></i>
                        {{ $showAddForm ? 'Close Form' : 'Add Budget Items' }}
                    </button>
                @else
                    <div></div>
                @endcan

                @if(count($pendingItems) > 0)
                    <div class="space-x-3">
                        <button wire:click="cancelAddItems"
                                wire:confirm="Are you sure you want to clear all pending items?"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200">
                            <i class="fas fa-times mr-2"></i>Clear Pending ({{ count($pendingItems) }})
                        </button>
                        @can('create_budget_items')
                            <button wire:click="saveAllItems"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                <i class="fas fa-save mr-2"></i>Submit Items for availability Check
                            </button>
                        @endcan
                    </div>
                @endif
            </div>

            <!-- Add Items Section: Form on Left, Pending Items on Right -->
            @if($showAddForm)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
                    <!-- Left: Add/Edit Form -->
                    <div class="bg-white rounded-lg shadow-md p-4">
                        <h3 class="text-lg font-semibold mb-3 text-gray-800">
                            @if($editingItemId)
                                <i class="fas fa-edit mr-2 text-blue-600"></i>Edit Budget Item
                            @elseif($editingPendingIndex !== null)
                                <i class="fas fa-edit mr-2 text-orange-600"></i>Edit Pending Item
                            @else
                                <i class="fas fa-plus mr-2 text-green-600"></i>Add Budget Item
                            @endif
                        </h3>

                        <form wire:submit.prevent="@if($editingItemId) updateBudgetItem @elseif($editingPendingIndex !== null) addItemToList @else addItemToList @endif">
                            <div class="space-y-3">
                                <!-- Currency Dropdown -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Currency</label>
                                    <select wire:model="currency" class="w-full px-2 py-1.5 text-sm border rounded">
                                        @foreach($currencies as $curr)
                                            <option value="{{ $curr['code'] }}">{{ $curr['code'] }} ({{ $curr['symbol'] }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Item Name -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Item Name *</label>
                                    <input type="text" wire:model="item_name"
                                           class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('item_name') border-red-500 @enderror">
                                    @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Category & Unit -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Category</label>
                                        <select wire:model="category"
                                                class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                            <option value="">Select</option>
                                            <option value="equipment">Equipment</option>
                                            <option value="materials">Materials</option>
                                            <option value="labor">Labor</option>
                                            <option value="services">Services</option>
                                            <option value="software">Software</option>
                                            <option value="other">Other</option>
                                        </select>
                                        @error('category') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Unit</label>
                                        <input type="text" wire:model="unit" placeholder="pcs, kg"
                                               class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        @error('unit') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Quantity & Unit Cost -->
                                <div class="grid grid-cols-2 gap-2">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Quantity *</label>
                                        <input type="number" wire:model="quantity" min="1"
                                               class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                                        @error('quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Unit Cost ({{ $currencySymbol }}) *</label>
                                        <input type="number" wire:model="unit_cost" step="0.01" min="0"
                                               class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit_cost') border-red-500 @enderror">
                                        @error('unit_cost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                    <textarea wire:model="description" rows="2"
                                              class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Justification -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Justification</label>
                                    <textarea wire:model="justification" rows="2"
                                              class="w-full px-2 py-1.5 text-sm border rounded focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                                              placeholder="Why is this item necessary?"></textarea>
                                    @error('justification') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Calculated Total -->
                                @if($quantity && $unit_cost)
                                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-3 rounded border border-blue-200">
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-medium text-gray-700">Total Cost:</span>
                                            <span class="text-xl font-bold text-blue-600">{{ $currencySymbol }}{{ number_format($quantity * $unit_cost, 2) }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Form Actions -->
                                <div class="flex space-x-2 pt-2">
                                    <button type="button" wire:click="toggleAddForm"
                                            class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded text-gray-700 hover:bg-gray-50 transition">
                                        Cancel
                                    </button>
                                    @if($editingItemId)
                                        <button type="submit"
                                                class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1.5 text-sm rounded transition duration-200">
                                            <i class="fas fa-save mr-1"></i>Update
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="flex-1 bg-green-600 hover:bg-green-700 text-white px-3 py-1.5 text-sm rounded transition duration-200">
                                            <i class="fas fa-plus mr-1"></i>{{ $editingPendingIndex !== null ? 'Update' : 'Add to List' }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Right: Pending Items List -->
                    <div class="bg-gradient-to-br from-orange-50 to-yellow-50 rounded-lg shadow-md p-4 border-2 border-orange-200">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-orange-800">
                                <i class="fas fa-list-ul mr-2"></i>Pending Items
                            </h3>
                            <span class="bg-orange-600 text-white px-2.5 py-0.5 rounded-full text-xs font-bold">
                                {{ count($pendingItems) }}
                            </span>
                        </div>

                        @if(count($pendingItems) > 0)
                            <div class="space-y-2 max-h-[500px] overflow-y-auto pr-1">
                                @foreach($pendingItems as $index => $item)
                                    <div class="bg-white rounded p-3 shadow-sm border border-orange-100 hover:shadow-md transition">
                                        <div class="flex justify-between items-start mb-1.5">
                                            <h4 class="font-semibold text-sm text-gray-900 flex-1">{{ $item['item_name'] }}</h4>
                                            @can('create_budget_items')
                                                <div class="flex space-x-1.5 ml-2">
                                                    <button wire:click="editPendingItem({{ $index }})"
                                                            class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-1 rounded text-xs">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button wire:click="removePendingItem({{ $index }})"
                                                            class="text-red-600 hover:text-red-800 hover:bg-red-50 p-1 rounded text-xs">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            @endcan
                                        </div>

                                        <div class="grid grid-cols-2 gap-1.5 text-xs text-gray-600 mb-1.5">
                                            <div>
                                                <span class="font-medium text-gray-700">Category:</span>
                                                <span class="ml-1">{{ $item['category'] ? ucfirst($item['category']) : 'N/A' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-700">Qty:</span>
                                                <span class="ml-1">{{ $item['quantity'] }} {{ $item['unit'] ?? '' }}</span>
                                            </div>
                                            <div>
                                                <span class="font-medium text-gray-700">Unit Cost:</span>
                                                <span class="ml-1">{{ $currencySymbol }}{{ number_format($item['unit_cost'], 2) }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="font-medium text-gray-700">Total:</span>
                                                <span class="ml-1 text-base font-bold text-orange-600">{{ $currencySymbol }}{{ number_format($item['total_cost'], 2) }}</span>
                                            </div>
                                        </div>

                                        @if($item['description'])
                                            <p class="text-xs text-gray-500 mt-1.5 border-t pt-1.5">{{ $item['description'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Pending Summary -->
                            <div class="mt-3 bg-orange-100 rounded p-3 border border-orange-300">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold text-sm text-orange-900">Pending Total:</span>
                                    <span class="text-xl font-bold text-orange-700">{{ $currencySymbol }}{{ number_format($pendingTotal, 2) }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p class="text-center text-xs">No pending items yet.<br>Add items using the form on the left.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Saved Budget Items List -->
            <div class="mb-4">
                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-list mr-2 text-blue-600"></i>Saved Budget Items
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">All saved and approved budget items for this project</p>
                    </div>
                </div>

                <!-- Search and Filter Bar -->
                <div class="bg-white rounded-lg shadow-md p-4 mb-3">
                    <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                        <div class="flex-1 w-full">
                            <div class="relative">
                                <input type="text"
                                    placeholder="Search items..."
                                    class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                                </svg>
                                Filter
                            </button>
                            <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                </svg>
                                Import
                            </button>
                            <button class="px-2 py-1.5 border border-gray-300 rounded-lg hover:bg-gray-50 flex items-center gap-1.5 text-xs text-gray-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Cost</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($budgetItems as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                    <td class="px-4 py-2 text-xs text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-4 py-2">
                                        <div class="text-xs font-medium text-gray-900">{{ $item->item_name }}</div>
                                        @if($item->description)
                                            <div class="text-xs text-gray-500 truncate max-w-xs">{{ Str::limit($item->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $item->category ? ucfirst($item->category) : 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $item->quantity }} {{ $item->unit }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $currencySymbol }}{{ number_format($item->unit_cost, 2) }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs font-semibold text-gray-900">{{ $currencySymbol }}{{ number_format($item->total_cost, 2) }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-1">
                                            @can('edit_budget_items')
                                                <button wire:click="editItem({{ $item->id }})"
                                                        class="px-2 py-1.5 text-xs text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                            @endcan
                                            @can('delete_budget_items')
                                                <button wire:click="deleteItem({{ $item->id }})"
                                                        wire:confirm="Are you sure you want to delete this item?"
                                                        class="px-2 py-1.5 text-xs text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            @endcan
                                            @cannot('edit_budget_items')
                                                @cannot('delete_budget_items')
                                                    <span class="text-xs text-gray-400">No actions</span>
                                                @endcannot
                                            @endcannot
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-gray-400 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            <p class="text-sm">No budget items added yet. Click "Add Budget Items" to get started.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('projects.list') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                </a>

                @if($budgetItems->count() > 0)
                    @can('submit_budget_for_approval')
                        <button wire:click="submitForApproval"
                                wire:confirm="Are you sure you want to submit this project budget for approval?"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition duration-200">
                            Submit for Approval <i class="fas fa-paper-plane ml-2"></i>
                        </button>
                    @endcan
                @endif
            </div>
        </div>
    </div>
</div>
