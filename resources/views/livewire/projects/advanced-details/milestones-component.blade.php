<div>
    <!-- Success/Error Messages -->
    @if (session()->has('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Project Milestones</h2>
            <button wire:click="openAddMilestoneModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus mr-2"></i>Add Milestone
            </button>
        </div>

        <!-- Milestones Table -->
        <div class="overflow-x-auto">
            @if($milestones->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Milestone
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Progress
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assigned To
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($milestones as $milestone)
                            <tr class="hover:bg-gray-50 transition {{ $milestone->status === 'completed' ? 'bg-green-50 hover:bg-green-100' : '' }}">
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-medium text-gray-900">{{ $milestone->name }}</span>
                                            @if($milestone->milestone_code)
                                                <span class="text-xs px-2 py-0.5 bg-gray-100 text-gray-600 rounded">{{ $milestone->milestone_code }}</span>
                                            @endif
                                        </div>
                                        @if($milestone->description)
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $milestone->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($milestone->status === 'completed') bg-green-100 text-green-800
                                        @elseif($milestone->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($milestone->status === 'delayed') bg-red-100 text-red-800
                                        @elseif($milestone->status === 'invoiced') bg-purple-100 text-purple-800
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($milestone->priority === 'critical') bg-red-100 text-red-800
                                        @elseif($milestone->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($milestone->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-gray-100 text-gray-600
                                        @endif">
                                        {{ ucfirst($milestone->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                    {{ $milestone->start_date?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                    {{ $milestone->due_date?->format('M d, Y') ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                    {{ $milestone->amount ? '$' . number_format($milestone->amount, 2) : '-' }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 bg-gray-200 rounded-full h-2 min-w-[60px]">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $milestone->progress_percentage }}%"></div>
                                        </div>
                                        <span class="text-xs font-medium text-gray-900 whitespace-nowrap">{{ $milestone->progress_percentage }}%</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $milestone->assignedTo->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="editMilestone({{ $milestone->id }})"
                                            class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 px-2 py-1 rounded transition"
                                            title="Edit Milestone">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteMilestone({{ $milestone->id }})"
                                            wire:confirm="Delete this milestone?"
                                            class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded transition"
                                            title="Delete Milestone">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-flag-checkered text-5xl mb-3"></i>
                    <p class="text-lg">No milestones yet</p>
                    <p class="text-sm">Add your first milestone to track project progress</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Add/Edit Milestone Modal -->
    @if($showMilestoneModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeMilestoneModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveMilestone">
                        <!-- Modal Header -->
                        <div class="bg-white px-6 pt-5 pb-4 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $editingMilestoneId ? 'Edit Milestone' : 'Add New Milestone' }}
                                </h3>
                                <button type="button" wire:click="closeMilestoneModal" class="text-gray-400 hover:text-gray-500">
                                    <i class="fas fa-times text-xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Modal Body -->
                        <div class="bg-white px-6 py-4 max-h-[70vh] overflow-y-auto">
                            <div class="space-y-6">
                                <!-- Basic Information Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Basic Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Name -->
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Milestone Name *</label>
                                            <input type="text" wire:model="milestone_name"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('milestone_name') border-red-500 @enderror"
                                                placeholder="Enter milestone name">
                                            @error('milestone_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>

                                        <!-- Description -->
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                            <textarea wire:model="milestone_description" rows="3"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Brief description of the milestone"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Timeline Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Timeline</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Start Date -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                            <input type="date" wire:model="milestone_start_date"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <!-- Due Date -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                            <input type="date" wire:model="milestone_due_date"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('milestone_due_date') border-red-500 @enderror">
                                            @error('milestone_due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Priority Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Status & Priority</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Status -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                            <select wire:model="milestone_status"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="pending">Pending</option>
                                                <option value="in_progress">In Progress</option>
                                                <option value="completed">Completed</option>
                                                <option value="delayed">Delayed</option>
                                                <option value="cancelled">Cancelled</option>
                                                <option value="invoiced">Invoiced</option>
                                            </select>
                                        </div>

                                        <!-- Priority -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                                            <select wire:model="milestone_priority"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="low">Low</option>
                                                <option value="medium">Medium</option>
                                                <option value="high">High</option>
                                                <option value="critical">Critical</option>
                                            </select>
                                        </div>

                                        <!-- Progress -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Progress *</label>
                                            <div class="relative">
                                                <input type="number" min="0" max="100" wire:model="milestone_progress"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="0">
                                                <span class="absolute right-3 top-2 text-sm text-gray-500">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Financial Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Financial</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <!-- Amount -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Amount (UGX)</label>
                                            <input type="number" step="0.01" wire:model="milestone_amount"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="0.00">
                                        </div>

                                        <!-- Percentage -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Budget %</label>
                                            <div class="relative">
                                                <input type="number" step="0.01" min="0" max="100" wire:model="milestone_percentage"
                                                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    placeholder="0.00">
                                                <span class="absolute right-3 top-2 text-sm text-gray-500">%</span>
                                            </div>
                                        </div>

                                        <!-- Is Billable -->
                                        <div class="flex items-center pt-6">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" wire:model="milestone_is_billable"
                                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                                <span class="text-sm font-medium text-gray-700">Is Billable</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Assignment & Dependencies Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Assignment & Dependencies</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Assigned To -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                                            <select wire:model="milestone_assigned_to"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">Select User</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Depends On -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Depends On Milestone</label>
                                            <select wire:model="milestone_depends_on"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                                <option value="">None</option>
                                                @foreach($availableMilestones as $ms)
                                                    <option value="{{ $ms->id }}">{{ $ms->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Details Section -->
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wider">Additional Details</h4>
                                    <div class="space-y-4">
                                        <!-- Deliverables -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Deliverables</label>
                                            <textarea wire:model="milestone_deliverables" rows="3"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="List expected deliverables for this milestone..."></textarea>
                                        </div>

                                        <!-- Notes -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                                            <textarea wire:model="milestone_notes" rows="2"
                                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                placeholder="Any additional notes or comments..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Footer -->
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                            <button type="button" wire:click="closeMilestoneModal"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                                Cancel
                            </button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-save mr-2"></i>
                                {{ $editingMilestoneId ? 'Update Milestone' : 'Create Milestone' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
