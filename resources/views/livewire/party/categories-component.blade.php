<div class="p-4 bg-base-100">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-black">Party Categories</h2>
            <p class="text-sm text-gray-700">Organize parties into meaningful groups</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="createNew" class="btn btn-primary">+ New Category</button>
        </div>
    </div>

    @if($confirmDeleteId)
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 flex items-center justify-between">
            <span class="text-sm">Delete this category? This cannot be undone.</span>
            <div class="flex gap-2">
                <button wire:click="deleteConfirmed" class="btn btn-error btn-sm">Delete</button>
                <button wire:click="$set('confirmDeleteId', null)" class="btn btn-ghost btn-sm">Cancel</button>
            </div>
        </div>
    @endif

    @if($showForm)
        <div class="mb-4 bg-white rounded-lg shadow-md border border-gray-200 p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Name</label>
                    <input type="text" wire:model.defer="name" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="e.g. Customer" />
                    @error('name') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
                <div>
                    <label class="block text-xs text-gray-600 mb-1">Description</label>
                    <input type="text" wire:model.defer="description" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Optional description" />
                    @error('description') <div class="text-xs text-red-600 mt-1">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2">
                <button wire:click="save" class="btn btn-primary btn-sm">Save</button>
                <button wire:click="cancel" class="btn btn-ghost btn-sm">Cancel</button>
            </div>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full">
                <div class="relative">
                    <input type="text" wire:model.debounce.300ms="query"
                           class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Search categories...">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <label class="text-xs text-gray-600">Show:</label>
                <select wire:model="perPage" class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parties</th>
                        <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse($items as $item)
                        <tr class="{{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                            <td class="px-3 py-1.5 text-sm text-black font-semibold">{{ $item->name }}</td>
                            <td class="px-3 py-1.5 text-gray-600">{{ $item->description ?: '—' }}</td>
                            <td class="px-3 py-1.5 text-gray-600">{{ $item->parties_count }}</td>
                            <td class="px-3 py-1.5">
                                <div class="flex items-center gap-2">
                                    <button wire:click="edit({{ $item->id }})" class="btn btn-ghost btn-xs">Edit</button>
                                    <button wire:click="confirmDelete({{ $item->id }})" class="btn btn-ghost btn-xs text-error">Delete</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-3 py-3 text-center text-gray-500">No categories found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
            @if(method_exists($items, 'links'))
                {{ $items->links() }}
            @endif
        </div>
    </div>
</div>
