<div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
                    <p class="text-gray-600">{{ $project->project_code }} - Item Availability Check</p>
                </div>
                <span class="px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                    {{ ucwords(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <!-- Instructions -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="font-semibold text-blue-900 mb-2">
                    <i class="fas fa-info-circle mr-2"></i>Implementation Team: Check Item Availability
                </h3>
                <p class="text-blue-800 text-sm">
                    Please verify the availability of each budget item with the stores team. Update the availability status for each item to proceed with the project.
                </p>
            </div>

            <!-- Items List with Availability Status -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Required Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Available Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @foreach($budgetItems as $index => $item)
                                <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
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
                                    <div class="text-xs text-gray-900"><span class="font-semibold">{{ $item->quantity }}</span> {{ $item->unit }}</div>
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs">
                                    @if($item->availability)
                                        <span class="font-semibold
                                            @if($item->availability->available_quantity >= $item->quantity) text-green-600
                                            @elseif($item->availability->available_quantity > 0) text-yellow-600
                                            @else text-red-600
                                            @endif">
                                            {{ $item->availability->available_quantity }} {{ $item->unit }}
                                        </span>
                                    @else
                                        <span class="text-gray-400">Not checked</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->availability)
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full
                                            @if($item->availability->availability_status === 'available') bg-green-100 text-green-800
                                            @elseif($item->availability->availability_status === 'partial') bg-yellow-100 text-yellow-800
                                            @elseif($item->availability->availability_status === 'ordered') bg-blue-100 text-blue-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($item->availability->availability_status) }}
                                        </span>
                                        <div class="text-xs text-gray-500 mt-1">
                                            by {{ $item->availability->checker->name }}
                                        </div>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button wire:click="checkAvailability({{ $item->id }})"
                                            class="text-blue-600 hover:text-blue-900 font-medium">
                                        <i class="fas fa-{{ $item->availability ? 'edit' : 'check' }} mr-1"></i>
                                        {{ $item->availability ? 'Update' : 'Check' }}
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>

            <!-- Availability Check Form Modal -->
            @if($selectedItemId)
                @php
                    $selectedItem = $budgetItems->firstWhere('id', $selectedItemId);
                @endphp

                <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
                     wire:click.self="$set('selectedItemId', null)">
                    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-xl font-semibold text-gray-900">
                                Check Availability: {{ $selectedItem->item_name }}
                            </h3>
                            <button wire:click="$set('selectedItemId', null)"
                                    class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form wire:submit.prevent="saveAvailability">
                            <div class="space-y-4">
                                <!-- Required Quantity (Read-only) -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Required Quantity</label>
                                    <input type="number" wire:model="required_quantity" readonly
                                           class="w-full px-4 py-2 border bg-gray-50 rounded-lg">
                                </div>

                                <!-- Available Quantity -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Quantity *</label>
                                    <input type="number" wire:model="available_quantity" min="0"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('available_quantity') border-red-500 @enderror">
                                    @error('available_quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Availability Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Availability Status *</label>
                                    <select wire:model="availability_status"
                                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('availability_status') border-red-500 @enderror">
                                        <option value="available">Available (Full quantity in stock)</option>
                                        <option value="partial">Partial (Some quantity available)</option>
                                        <option value="unavailable">Unavailable (Need to order)</option>
                                        <option value="ordered">Ordered (Waiting for delivery)</option>
                                    </select>
                                    @error('availability_status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Expected Availability Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Expected Availability Date</label>
                                    <input type="date" wire:model="expected_availability_date"
                                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    @error('expected_availability_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    <p class="text-sm text-gray-500 mt-1">Leave blank if item is immediately available</p>
                                </div>

                                <!-- Notes -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea wire:model="notes" rows="3"
                                              class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Add any additional information about this item's availability..."></textarea>
                                    @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <!-- Availability Summary -->
                                @if($available_quantity !== '' && $required_quantity)
                                    <div class="p-4 rounded-lg
                                        @if($available_quantity >= $required_quantity) bg-green-50 border border-green-200
                                        @elseif($available_quantity > 0) bg-yellow-50 border border-yellow-200
                                        @else bg-red-50 border border-red-200
                                        @endif">
                                        <p class="text-sm font-medium
                                            @if($available_quantity >= $required_quantity) text-green-800
                                            @elseif($available_quantity > 0) text-yellow-800
                                            @else text-red-800
                                            @endif">
                                            @if($available_quantity >= $required_quantity)
                                                <i class="fas fa-check-circle mr-2"></i>Sufficient quantity available
                                            @elseif($available_quantity > 0)
                                                <i class="fas fa-exclamation-triangle mr-2"></i>Only {{ $available_quantity }} of {{ $required_quantity }} available
                                            @else
                                                <i class="fas fa-times-circle mr-2"></i>Item not available in stock
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" wire:click="$set('selectedItemId', null)"
                                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-save mr-2"></i>Save Availability
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Progress Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold mb-4">Availability Check Progress</h3>
                @php
                    $totalItems = $budgetItems->count();
                    $checkedItems = $budgetItems->filter(fn($item) => $item->availability)->count();
                    $availableItems = $budgetItems->filter(fn($item) => $item->availability && $item->availability->availability_status === 'available')->count();
                    $progress = $totalItems > 0 ? round(($checkedItems / $totalItems) * 100) : 0;
                @endphp

                <div class="mb-4">
                    <div class="flex justify-between text-sm text-gray-600 mb-2">
                        <span>Checked: {{ $checkedItems }} / {{ $totalItems }} items</span>
                        <span>{{ $progress }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4 text-center">
                    <div class="bg-green-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-green-600">{{ $availableItems }}</p>
                        <p class="text-sm text-gray-600">Available</p>
                    </div>
                    <div class="bg-yellow-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-yellow-600">
                            {{ $budgetItems->filter(fn($item) => $item->availability && in_array($item->availability->availability_status, ['partial', 'ordered']))->count() }}
                        </p>
                        <p class="text-sm text-gray-600">Partial/Ordered</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <p class="text-2xl font-bold text-red-600">
                            {{ $budgetItems->filter(fn($item) => $item->availability && $item->availability->availability_status === 'unavailable')->count() }}
                        </p>
                        <p class="text-sm text-gray-600">Unavailable</p>
                    </div>
                </div>

                @if($checkedItems === $totalItems && $availableItems === $totalItems)
                    <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-green-800 font-medium">
                            <i class="fas fa-check-circle mr-2"></i>
                            All items are available! The project status has been updated to "In Progress".
                        </p>
                    </div>
                @endif
            </div>

            <!-- Navigation -->
            <div class="flex justify-between items-center">
                <a href="{{ route('projects.list') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                </a>
            </div>
        </div>
    </div>
</div>
