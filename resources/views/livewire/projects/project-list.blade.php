<div wire:key="projects-list-root" id="projects-list-root">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="w-full mx-0 px-2 md:px-6">
            <div class="flex items-center justify-between py-4">
                <div>
                    <h1 class="text-2xl font-bold text-black">Projects</h1>
                    <p class="text-sm text-gray-500">Manage and track all your projects</p>
                </div>
            </div>


        </div>
    </div>
    <!-- Main Content -->
    <!-- Tab Menu -->
    <div class="mb-4">
        <div class="flex items-center gap-2 border-b border-gray-200">
            <button wire:click="$set('activeTab', 'list')"
                class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ ($activeTab ?? 'list') === 'list' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Projects
            </button>
            <button wire:click="$set('activeTab', 'create')"
                class="px-6 py-3 text-sm font-medium border-b-2 transition-colors {{ ($activeTab ?? 'list') === 'create' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 inline-block mr-2" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Project
            </button>
        </div>
    </div>
    <div class="p-4 sm:p-6">

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('message') }}
            </div>
        @endif

        @if (($activeTab ?? 'list') === 'list')
            <!-- Projects List Tab -->
            <!-- Filters -->
            <div id='filter-id' class="bg-white rounded shadow p-2 mb-3">
                <div class="flex items-center gap-2 flex-1 w-full">
                    <div class="relative flex-1">
                        <input type="text" wire:model.live="search" placeholder="Search..."
                            class="w-full px-2 py-1 text-sm border rounded focus:ring-1 focus:ring-blue-400 @if($search) pr-20 @endif">
                        @if($search)
                            <button wire:click="$set('search', '')" 
                                class="px-2 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-1 transition-colors duration-200">
                                Clear search
                            </button>
                        @endif
                    </div>
                    <div class="flex-1">
                        <select wire:model.live="statusFilter"
                            class="w-full px-2 py-1 text-sm border rounded focus:ring-1 focus:ring-blue-400">
                            <option value="">All Statuses</option>
                            <option value="draft">Draft</option>
                            <option value="budget_planning">Budget Planning</option>
                            <option value="pending_approval">Pending Approval</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                            <option value="in_progress">In Progress</option>
                            <option value="checking_availability">Checking Availability</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <select wire:model.live="priorityFilter"
                            class="w-full px-2 py-1 text-sm border rounded focus:ring-1 focus:ring-blue-400">
                            <option value="">All Priorities</option>
                            <option value="low">Low</option>
                            <option value="medium">Medium</option>
                            <option value="high">High</option>
                            <option value="critical">Critical</option>
                        </select>
                    </div>
                    <div>
                        <button wire:click="clearFilters" 
                            class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 border rounded transition whitespace-nowrap">
                            Clear Filters
                        </button>
                    </div>
                </div>
            </div>

            <!-- Projects List -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                            <tr>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customers</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Start Date</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Budget</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Priority</th>
                                <th
                                    class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse($projects as $index => $project)
                                <tr
                                    class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                    <td class="px-4 py-2">
                                        <a href="{{ route('projects.view', $project->id) }}" class="hover:underline">
                                            <div class="text-xs font-medium text-blue-600 hover:text-blue-800">
                                                {{ $project->name }}</div>
                                        </a>
                                        <div class="text-xs text-gray-500">{{ $project->project_code }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                                {{ $project->client_services_count ?? 0 }}
                                            </span>
                                            <span class="text-xs text-gray-500">Customers</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">{{ $project->start_date->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <div class="text-xs text-gray-900">
                                            ${{ number_format($project->budgetItems->sum('total_cost'), 2) }}</div>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($project->status === 'approved') bg-green-100 text-green-800
                                    @elseif($project->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($project->status === 'pending_approval') bg-yellow-100 text-yellow-800
                                    @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucwords(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if ($project->priority === 'critical') bg-red-100 text-red-800
                                    @elseif($project->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($project->priority) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-right text-xs font-medium">
                                        <div class="flex items-center justify-end gap-1">
                                            <a href="{{ route('projects.view', $project->id) }}"
                                                class="px-2 py-1.5 text-xs text-gray-600 hover:text-gray-900 hover:bg-gray-50 rounded transition"
                                                title="View Details">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>
                                            @if ($project->status === 'draft')
                                                <a href="{{ route('projects.budget', $project->id) }}"
                                                    class="px-2 py-1.5 text-xs text-blue-600 hover:text-blue-900 hover:bg-blue-50 rounded transition"
                                                    title="Budget">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                            @elseif($project->status === 'pending_approval')
                                                <a href="{{ route('projects.approvals', $project->id) }}"
                                                    class="px-2 py-1.5 text-xs text-purple-600 hover:text-purple-900 hover:bg-purple-50 rounded transition"
                                                    title="Approve">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                            @elseif($project->status === 'approved')
                                                <a href="{{ route('projects.availability', $project->id) }}"
                                                    class="px-2 py-1.5 text-xs text-green-600 hover:text-green-900 hover:bg-green-50 rounded transition"
                                                    title="Check Items">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                    </svg>
                                                </a>
                                            @elseif($project->status === 'in_progress')
                                                <a href="{{ route('projects.view', $project->id) }}"
                                                    class="px-2 py-1.5 text-xs text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 rounded transition"
                                                    title="Manage Progress">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                    </svg>
                                                </a>
                                            @endif

                                            <!-- Update Status Button - Available for all statuses -->
                                            <button wire:click="openUpdateStatusModal({{ $project->id }})"
                                                class="px-2 py-1.5 text-xs text-yellow-600 hover:text-yellow-900 hover:bg-yellow-50 rounded transition"
                                                title="Update Status">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                                </svg>
                                            </button>

                                            <!-- Complete Project Button - Available for all statuses -->
                                            <button wire:click="completeProject({{ $project->id }})"
                                                wire:confirm="Are you sure you want to mark this project as completed?"
                                                class="px-2 py-1.5 text-xs text-teal-600 hover:text-teal-900 hover:bg-teal-50 rounded transition"
                                                title="Complete Project">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </button>

                                            <button wire:click="deleteProject({{ $project->id }})"
                                                wire:confirm="Are you sure you want to delete this project?"
                                                class="px-2 py-1.5 text-xs text-red-600 hover:text-red-900 hover:bg-red-50 rounded transition">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5"
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
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        No projects found. Create your first project to get started.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $projects->links() }}
                </div>
            </div>
        @elseif (($activeTab ?? 'list') === 'create')
            <!-- Create Project Tab -->
            @include('livewire.projects.create-project')
        @endif

        <!-- Create Project Modal -->
        @if ($showCreateModal)
            <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                        wire:click="closeCreateModal"></div>

                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form wire:submit.prevent="createProject">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        Create New Project
                                    </h3>
                                    <button type="button" wire:click="closeCreateModal"
                                        class="text-gray-400 hover:text-gray-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-[60vh] overflow-y-auto pr-2">
                                    <!-- Project Name -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Project Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" wire:model="name"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                            placeholder="e.g., New Office Network Setup">
                                        @error('name')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                                        <textarea wire:model="description" rows="2"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="Brief description of the project"></textarea>
                                        @error('description')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Start Date -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Start Date <span class="text-red-500">*</span>
                                        </label>
                                        <input type="date" wire:model="start_date"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('start_date')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- End Date -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">End Date
                                            (Expected)</label>
                                        <input type="date" wire:model="end_date"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        @error('end_date')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Estimated Budget -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Estimated Budget
                                            (UGX)</label>
                                        <input type="number" step="0.01" wire:model="estimated_budget"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="0.00">
                                        @error('estimated_budget')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Priority -->
                                    <div>
                                        <label class="block text-xs font-medium text-gray-700 mb-1">
                                            Priority <span class="text-red-500">*</span>
                                        </label>
                                        <select wire:model="priority"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="low">Low</option>
                                            <option value="medium">Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                        @error('priority')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Client -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Client</label>
                                        <select wire:model="client_id"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                            <option value="">Select a client (optional)</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Objectives -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Project
                                            Objectives</label>
                                        <textarea wire:model="objectives" rows="2"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="What are the main goals of this project?"></textarea>
                                        @error('objectives')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Deliverables -->
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-medium text-gray-700 mb-1">Expected
                                            Deliverables</label>
                                        <textarea wire:model="deliverables" rows="2"
                                            class="w-full px-3 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="What will be delivered at the end of this project?"></textarea>
                                        @error('deliverables')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                    Create Project
                                </button>
                                <button type="button" wire:click="closeCreateModal"
                                    class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <!-- Update Status Modal -->
        @if ($showUpdateStatusModal)
            <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                aria-modal="true">
                <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                        wire:click="closeUpdateStatusModal"></div>

                    <div
                        class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <form wire:submit.prevent="updateProjectStatus">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                                            Update Project Status
                                        </h3>

                                        <div class="mt-4">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Select New Status
                                            </label>
                                            <select wire:model="selectedProjectStatus"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                                <option value="">Select Status</option>
                                                <option value="draft">Draft</option>
                                                <option value="budget_planning">Budget Planning</option>
                                                <option value="pending_approval">Pending Approval</option>
                                                <option value="approved">Approved</option>
                                                <option value="rejected">Rejected</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="checking_availability">Checking Availability</option>
                                                <option value="on_hold">On Hold</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                            @error('selectedProjectStatus')
                                                <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                <button type="submit"
                                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                    Update Status
                                </button>
                                <button type="button" wire:click="closeUpdateStatusModal"
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
