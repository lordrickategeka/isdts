<div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">Create New Project</h1>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="createProject" class="bg-white rounded-lg shadow-md p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Project Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Name *</label>
                        <input type="text" wire:model="name"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea wire:model="description" rows="4"
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('description') border-red-500 @enderror"></textarea>
                        @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Start Date *</label>
                        <input type="date" wire:model="start_date"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror">
                        @error('start_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">End Date (Expected)</label>
                        <input type="date" wire:model="end_date"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror">
                        @error('end_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Estimated Budget -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Budget ($)</label>
                        <input type="number" step="0.01" wire:model="estimated_budget"
                               class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('estimated_budget') border-red-500 @enderror">
                        @error('estimated_budget') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priority *</label>
                        <select wire:model="priority"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('priority') border-red-500 @enderror">
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                        @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Client -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Client</label>
                        <select wire:model="client_id"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('client_id') border-red-500 @enderror">
                            <option value="">Select a client (optional)</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                        @error('client_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Objectives -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Project Objectives</label>
                        <textarea wire:model="objectives" rows="3"
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('objectives') border-red-500 @enderror"
                                  placeholder="What are the main goals of this project?"></textarea>
                        @error('objectives') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- Deliverables -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expected Deliverables</label>
                        <textarea wire:model="deliverables" rows="3"
                                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('deliverables') border-red-500 @enderror"
                                  placeholder="What will be delivered at the end of this project?"></textarea>
                        @error('deliverables') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex justify-between items-center mt-6 pt-6 border-t">
                    <a href="{{ route('projects.list') }}" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                        Create Project & Add Budget Items
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
