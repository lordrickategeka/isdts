<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h2 class="text-2xl font-bold text-black">User Role Management</h2>
                <p class="text-sm text-gray-500">Assign roles to users - permissions are automatically inherited from roles</p>
            </div>
        </div>
    </div>

    <div class="p-6">

    <!-- Flash Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search -->
    <div class="mb-4">
        <input type="text" wire:model.live="search" placeholder="Search users by name or email..."
               class="w-full md:w-1/3 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Roles</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Permissions</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @forelse ($user->roles as $role)
                                    <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">{{ $role->name }}</span>
                                @empty
                                    <span class="text-sm text-gray-400">No roles</span>
                                @endforelse
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-600">{{ $user->getAllPermissions()->count() }} permissions</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button wire:click="openModal({{ $user->id }})" class="text-blue-600 hover:text-blue-900">
                                Manage
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            No users found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal -->
    @if ($showModal && $selectedUser)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full">
                    <form wire:submit.prevent="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="mb-4">
                                <h3 class="text-lg font-medium text-gray-900">Manage User Access</h3>
                                <p class="text-sm text-gray-600">{{ $selectedUser->name }} ({{ $selectedUser->email }})</p>
                            </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Roles -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Assign Roles</label>
                            <p class="text-xs text-gray-500 mb-3">Select roles to assign. All permissions from selected roles will be automatically granted.</p>
                            <div class="border border-gray-300 rounded-md p-4 max-h-96 overflow-y-auto">
                                @if ($roles->count() > 0)
                                    @foreach ($roles as $role)
                                        <label class="flex items-start space-x-2 hover:bg-gray-50 p-2 rounded cursor-pointer mb-2">
                                            <input type="checkbox" wire:model="selectedRoles" value="{{ $role->name }}"
                                                   class="mt-1 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-700">{{ $role->name }}</span>
                                                <span class="text-xs text-gray-500 block">{{ $role->permissions->count() }} permissions</span>
                                            </div>
                                        </label>
                                    @endforeach
                                @else
                                    <p class="text-gray-500 text-sm">No roles available.</p>
                                @endif
                            </div>
                        </div>

                        <!-- Permissions Preview -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Permissions from Selected Roles</label>
                            <p class="text-xs text-gray-500 mb-3">These permissions will be automatically granted based on role selection.</p>
                            <div class="border border-gray-300 rounded-md p-4 max-h-96 overflow-y-auto bg-gray-50">
                                @php
                                    $rolePermissions = collect();
                                    foreach ($selectedRoles as $roleName) {
                                        $role = $roles->firstWhere('name', $roleName);
                                        if ($role) {
                                            $rolePermissions = $rolePermissions->merge($role->permissions);
                                        }
                                    }
                                    $rolePermissions = $rolePermissions->unique('id')->sortBy('name');
                                @endphp

                                @if ($rolePermissions->count() > 0)
                                    @foreach ($rolePermissions as $permission)
                                        <div class="flex items-center space-x-2 p-2 mb-1">
                                            <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">{{ $permission->name }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="text-gray-400 text-sm text-center py-4">No roles selected</p>
                                @endif
                            </div>
                        </div>
                    </div>

                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                Update Roles
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    </div>
</div>
