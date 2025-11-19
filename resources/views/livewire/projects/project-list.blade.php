<div>
    <div class="container mx-auto px-4 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Projects</h1>
            <a href="{{ route('projects.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-plus mr-2"></i>New Project
            </a>
        </div>

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input type="text" wire:model.live="search" placeholder="Search projects..."
                           class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <select wire:model.live="statusFilter" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
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
                <div>
                    <select wire:model.live="priorityFilter" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Projects List -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $project->name }}</div>
                                <div class="text-sm text-gray-500">{{ $project->project_code }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $project->client?->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $project->start_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                ${{ number_format($project->budgetItems->sum('total_cost'), 2) }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($project->status === 'approved') bg-green-100 text-green-800
                                    @elseif($project->status === 'rejected') bg-red-100 text-red-800
                                    @elseif($project->status === 'pending_approval') bg-yellow-100 text-yellow-800
                                    @elseif($project->status === 'in_progress') bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucwords(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    @if($project->priority === 'critical') bg-red-100 text-red-800
                                    @elseif($project->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($project->priority === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($project->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                @if($project->status === 'draft')
                                    <a href="{{ route('projects.budget', $project->id) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-dollar-sign"></i> Budget
                                    </a>
                                @elseif($project->status === 'pending_approval')
                                    <a href="{{ route('projects.approvals', $project->id) }}" class="text-purple-600 hover:text-purple-900">
                                        <i class="fas fa-check-circle"></i> Approve
                                    </a>
                                @elseif($project->status === 'approved')
                                    <a href="{{ route('projects.availability', $project->id) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-box"></i> Check Items
                                    </a>
                                @endif
                                <button wire:click="deleteProject({{ $project->id }})"
                                        wire:confirm="Are you sure you want to delete this project?"
                                        class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
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
</div>
