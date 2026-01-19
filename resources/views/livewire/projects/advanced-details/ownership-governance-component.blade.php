<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Ownership & Governance</h2>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus mr-2"></i>Add Person
            </button>
        </div>

        <!-- Project Persons List -->
        <div class="space-y-4">
            @forelse($projectPersons as $person)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $person->user->name ?? $person->name }}</h3>
                            <p class="text-sm text-gray-600">{{ ucfirst($person->role) }}</p>
                            @if($person->responsibilities)
                                <p class="text-sm text-gray-500 mt-2">{{ $person->responsibilities }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-users text-5xl mb-3"></i>
                    <p class="text-lg">No team members assigned yet</p>
                    <p class="text-sm">Add team members and define their roles</p>
                </div>
            @endforelse
        </div>

        <!-- Project Hierarchy Section -->
        <div class="mt-6">
            <h3 class="text-lg font-semibold text-gray-800">Project Hierarchy</h3>
            <ul class="list-disc pl-6">
                @forelse($projectHierarchy as $childProject)
                    <li>
                        <span class="font-medium">{{ $childProject->name }}</span>
                        @if($childProject->children->isNotEmpty())
                            <ul class="list-disc pl-6">
                                @foreach($childProject->children as $grandChildProject)
                                    <li>{{ $grandChildProject->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @empty
                    <li class="text-gray-500">No child projects found</li>
                @endforelse
            </ul>
        </div>

        <!-- Add Hierarchy Modal -->
        <div x-data="{ open: false }">
            <!-- Trigger Button -->
            <button @click="open = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-sitemap mr-2"></i>Add Hierarchy
            </button>

            <!-- Modal -->
            <div x-show="open" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
                <div class="bg-white rounded-lg shadow-lg w-1/3 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Add Project Hierarchy</h3>

                    <form wire:submit.prevent="addHierarchy">
                        <div class="mb-4">
                            <label for="childProject" class="block text-sm font-medium text-gray-700">Child Project</label>
                            <input type="text" id="childProject" wire:model.defer="newChildProject.name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            @error('newChildProject.name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Add People Details to Hierarchy Modal -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold text-gray-800">Add People to Project</h3>
                            <ul class="list-disc pl-6">
                                <form wire:submit.prevent="addPerson">
                                    <!-- Role Type -->
                                    <div class="mb-4">
                                        <label for="roleType" class="block text-sm font-medium text-gray-700">Role Type</label>
                                        <input type="text" id="roleType" wire:model.defer="newPerson.role_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @error('newPerson.role_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Client ID -->
                                    <div class="mb-4">
                                        <label for="clientId" class="block text-sm font-medium text-gray-700">Client</label>
                                        <select id="clientId" wire:model.defer="newPerson.client_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Select Client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('newPerson.client_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- User ID -->
                                    <div class="mb-4">
                                        <label for="userId" class="block text-sm font-medium text-gray-700">User</label>
                                        <select id="userId" wire:model.defer="newPerson.user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                            <option value="">Select User</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('newPerson.user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Responsibility -->
                                    <div class="mb-4">
                                        <label for="responsibility" class="block text-sm font-medium text-gray-700">Responsibility</label>
                                        <textarea id="responsibility" wire:model.defer="newPerson.responsibility" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"></textarea>
                                        @error('newPerson.responsibility') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Assigned Date -->
                                    <div class="mb-4">
                                        <label for="assignedDate" class="block text-sm font-medium text-gray-700">Assigned Date</label>
                                        <input type="date" id="assignedDate" wire:model.defer="newPerson.assigned_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @error('newPerson.assigned_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- End Date -->
                                    <div class="mb-4">
                                        <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" id="endDate" wire:model.defer="newPerson.end_date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                        @error('newPerson.end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <!-- Is Active -->
                                    <div class="mb-4">
                                        <label for="isActive" class="block text-sm font-medium text-gray-700">Is Active</label>
                                        <input type="checkbox" id="isActive" wire:model.defer="newPerson.is_active" class="mt-1">
                                        @error('newPerson.is_active') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="flex justify-end">
                                        <button type="button" @click="open = false" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg text-sm mr-2">Cancel</button>
                                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">Save</button>
                                    </div>
                                </form>
                            </ul>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
