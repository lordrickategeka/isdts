<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Vendors</h1>
                <p class="text-sm text-gray-500">Manage vendor information and services</p>
            </div>
            <button wire:click="openCreateModal"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add Vendor
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-7xl mx-auto">
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="bg-white rounded-lg shadow-md p-4 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-medium text-gray-700 mb-1">Search</label>
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Search by name, code, email, or phone..."
                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                        <select wire:model.live="statusFilter"
                            class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Vendors Table -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    #</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Vendor Name/Code</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Contact</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Services</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($vendors as $index => $vendor)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span class="text-xs text-gray-500">{{ $vendors->firstItem() + $index }}</span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="text-xs font-medium text-gray-900">{{ $vendor->name }}</div>
                                        <span
                                            class="text-xs font-medium text-blue-600">{{ $vendor->vendor_code }}</span>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="text-xs text-gray-900">
                                            @if ($vendor->email)
                                                <div class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3 h-3 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    <span>{{ $vendor->email }}</span>
                                                </div>
                                            @endif
                                            @if ($vendor->phone)
                                                <div class="flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-3 h-3 text-gray-400" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                    </svg>
                                                    <span>{{ $vendor->phone }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-2">
                                        <div class="text-xs text-gray-900">
                                            @if ($vendor->services_count > 0)
                                                @foreach ($vendor->services as $service)
                                                    <div class="mb-1">
                                                        <span
                                                            class="items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                            {{ $service->service_name }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            @else
                                                <span class="text-xs text-gray-400">No services</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $vendor->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($vendor->status) }}
                                        </span>
                                    </td>
                                    <td class="px-3 py-2 whitespace-nowrap text-xs font-medium">
                                        <div class="flex items-center gap-2">
                                            <button wire:click="openEditModal({{ $vendor->id }})"
                                                class="text-blue-600 hover:text-blue-900" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <button wire:click="delete({{ $vendor->id }})"
                                                wire:confirm="Are you sure you want to delete this vendor?"
                                                class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                        <p>No vendors found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 border-t border-gray-200 flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $vendors->firstItem() ?? 0 }}</span> to <span
                            class="font-medium">{{ $vendors->lastItem() ?? 0 }}</span> of <span
                            class="font-medium">{{ $vendors->total() }}</span> results
                    </div>
                    <div>
                        {{ $vendors->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <!-- Modal panel -->
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="save">
                        <!-- Modal Header -->
                        <div class="bg-white px-4 py-3 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-semibold text-gray-900">
                                    {{ $isEdit ? 'Edit Vendor' : 'Add New Vendor' }}
                                </h3>
                                <button type="button" wire:click="closeModal"
                                    class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-white px-4 py-5 sm:p-6 max-h-[70vh] overflow-y-auto">
                            <div class="space-y-4">
                                <!-- Vendor Information -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Vendor Information</h4>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">
                                                Vendor Name <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" wire:model="name"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Enter vendor name">
                                            @error('name')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 mb-1">Email</label>
                                                <input type="email" wire:model="email"
                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="vendor@email.com">
                                                @error('email')
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 mb-1">Phone</label>
                                                <input type="tel" wire:model="phone"
                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="+256 XXX XXXXXX">
                                                @error('phone')
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Address</label>
                                            <textarea wire:model="address" rows="2"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Vendor address"></textarea>
                                            @error('address')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Status <span
                                                    class="text-red-500">*</span></label>
                                            <select wire:model="status"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                            @error('status')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div>
                                            <label class="block text-xs font-medium text-gray-700 mb-1">Notes</label>
                                            <textarea wire:model="notes" rows="2"
                                                class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                placeholder="Additional notes"></textarea>
                                            @error('notes')
                                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Services Section -->
                                <div class="pt-4 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-800 mb-3">Services</h4>

                                    <!-- Add Service Form -->
                                    <div class="bg-gray-50 rounded-lg p-3 mb-3">
                                        <div class="space-y-2">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-700 mb-1">Service
                                                    Name</label>
                                                <input type="text" wire:model="newServiceName"
                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="e.g., Installation, Maintenance">
                                                @error('newServiceName')
                                                    <span class="text-red-500 text-xs">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                                <input type="text" wire:model="newServiceDescription"
                                                    class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                                    placeholder="Service description">
                                            </div>
                                            <button type="button" wire:click="addService"
                                                class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs rounded-lg font-medium flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4" />
                                                </svg>
                                                Add Service
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Services List -->
                                    @if (count($services) > 0)
                                        <div class="space-y-2">
                                            @foreach ($services as $index => $service)
                                                <div
                                                    class="bg-white border border-gray-200 rounded-lg p-3 flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $service['service_name'] }}</div>
                                                        @if ($service['description'])
                                                            <div class="text-xs text-gray-500 mt-1">
                                                                {{ $service['description'] }}</div>
                                                        @endif
                                                    </div>
                                                    <button type="button"
                                                        wire:click="removeService({{ $index }})"
                                                        class="text-red-500 hover:text-red-700 ml-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-center py-4 text-gray-400 text-sm">
                                            No services added yet
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-200">
                            <button type="submit"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto">
                                {{ $isEdit ? 'Update' : 'Create' }}
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
