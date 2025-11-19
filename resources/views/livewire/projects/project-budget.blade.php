<div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
                    <p class="text-gray-600">{{ $project->project_code }} - Project Budget Planning</p>
                </div>
                <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                    {{ ucwords(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>

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
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700">Total Budget</h3>
                        <p class="text-3xl font-bold text-blue-600">${{ number_format($totalBudget, 2) }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600">Budget Items</p>
                        <p class="text-2xl font-semibold text-gray-800">{{ $budgetItems->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Add Budget Item Button -->
            <div class="mb-4">
                <button wire:click="toggleAddForm"
                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                    <i class="fas {{ $showAddForm ? 'fa-times' : 'fa-plus' }} mr-2"></i>
                    {{ $showAddForm ? 'Cancel' : 'Add Budget Item' }}
                </button>
            </div>

            <!-- Add/Edit Budget Item Form -->
            @if($showAddForm)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">{{ $editingItemId ? 'Edit' : 'Add' }} Budget Item</h3>

                    <form wire:submit.prevent="{{ $editingItemId ? 'updateBudgetItem' : 'addBudgetItem' }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Item Name -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Item Name *</label>
                                <input type="text" wire:model="item_name"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('item_name') border-red-500 @enderror">
                                @error('item_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                <select wire:model="category"
                                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Category</option>
                                    <option value="equipment">Equipment</option>
                                    <option value="materials">Materials</option>
                                    <option value="labor">Labor</option>
                                    <option value="services">Services</option>
                                    <option value="software">Software</option>
                                    <option value="other">Other</option>
                                </select>
                                @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Unit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                                <input type="text" wire:model="unit" placeholder="e.g., pieces, kg, hours"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @error('unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                                <input type="number" wire:model="quantity" min="1"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('quantity') border-red-500 @enderror">
                                @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Unit Cost -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Unit Cost ($) *</label>
                                <input type="number" wire:model="unit_cost" step="0.01" min="0"
                                       class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('unit_cost') border-red-500 @enderror">
                                @error('unit_cost') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                <textarea wire:model="description" rows="2"
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                                @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Justification -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Justification</label>
                                <textarea wire:model="justification" rows="2"
                                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                          placeholder="Why is this item necessary for the project?"></textarea>
                                @error('justification') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- Calculated Total -->
                            @if($quantity && $unit_cost)
                                <div class="md:col-span-2 bg-blue-50 p-4 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        Total Cost: <span class="text-xl font-bold text-blue-600">${{ number_format($quantity * $unit_cost, 2) }}</span>
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-end space-x-3 mt-4">
                            <button type="button" wire:click="toggleAddForm"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                {{ $editingItemId ? 'Update' : 'Add' }} Item
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Budget Items List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($budgetItems as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                    @if($item->description)
                                        <div class="text-sm text-gray-500">{{ Str::limit($item->description, 50) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $item->category ? ucfirst($item->category) : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $item->quantity }} {{ $item->unit }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    ${{ number_format($item->unit_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-semibold text-gray-900">
                                    ${{ number_format($item->total_cost, 2) }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium space-x-3">
                                    <button wire:click="editItem({{ $item->id }})"
                                            class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button wire:click="deleteItem({{ $item->id }})"
                                            wire:confirm="Are you sure you want to delete this item?"
                                            class="text-red-600 hover:text-red-900">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>No budget items added yet. Click "Add Budget Item" to get started.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('projects.list') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                </a>

                @if($budgetItems->count() > 0)
                    <button wire:click="submitForApproval"
                            wire:confirm="Are you sure you want to submit this project budget for approval?"
                            class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        Submit for Approval <i class="fas fa-paper-plane ml-2"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
