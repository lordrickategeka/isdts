<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Project Milestones</h2>
            <button wire:click="openAddMilestoneModal" 
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus mr-2"></i>Add Milestone
            </button>
        </div>

        <!-- Milestones List -->
        <div class="space-y-4">
            @forelse($milestones as $milestone)
                <div class="border rounded-lg p-4 hover:shadow-md transition {{ $milestone->status === 'completed' ? 'bg-green-50' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-lg font-medium text-gray-900">{{ $milestone->name }}</h3>
                                @if($milestone->milestone_code)
                                    <span class="text-xs px-2 py-1 bg-gray-100 text-gray-600 rounded">{{ $milestone->milestone_code }}</span>
                                @endif
                                <span class="text-xs px-2 py-1 rounded-full
                                    @if($milestone->status === 'completed') bg-green-100 text-green-800
                                    @elseif($milestone->status === 'in_progress') bg-blue-100 text-blue-800
                                    @elseif($milestone->status === 'delayed') bg-red-100 text-red-800
                                    @elseif($milestone->status === 'invoiced') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $milestone->status)) }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded
                                    @if($milestone->priority === 'critical') bg-red-100 text-red-800
                                    @elseif($milestone->priority === 'high') bg-orange-100 text-orange-800
                                    @elseif($milestone->priority === 'medium') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-600
                                    @endif">
                                    {{ ucfirst($milestone->priority) }}
                                </span>
                            </div>

                            @if($milestone->description)
                                <p class="text-sm text-gray-600 mb-3">{{ $milestone->description }}</p>
                            @endif

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-3">
                                <div>
                                    <span class="text-xs text-gray-500">Start Date</span>
                                    <p class="text-sm font-medium">{{ $milestone->start_date?->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Due Date</span>
                                    <p class="text-sm font-medium">{{ $milestone->due_date?->format('M d, Y') ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Amount</span>
                                    <p class="text-sm font-medium">{{ $milestone->amount ? '$' . number_format($milestone->amount, 2) : 'N/A' }}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Assigned To</span>
                                    <p class="text-sm font-medium">{{ $milestone->assignedTo->name ?? 'Unassigned' }}</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mb-3">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs text-gray-600">Progress</span>
                                    <span class="text-xs font-medium text-gray-900">{{ $milestone->progress_percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $milestone->progress_percentage }}%"></div>
                                </div>
                            </div>

                            @if($milestone->deliverables)
                                <div class="text-xs text-gray-600">
                                    <strong>Deliverables:</strong> {{ Str::limit($milestone->deliverables, 100) }}
                                </div>
                            @endif
                        </div>

                        <div class="flex gap-2 ml-4">
                            <button wire:click="editMilestone({{ $milestone->id }})" 
                                class="text-blue-600 hover:text-blue-800 p-2" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button wire:click="deleteMilestone({{ $milestone->id }})" 
                                wire:confirm="Delete this milestone?"
                                class="text-red-600 hover:text-red-800 p-2" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-flag-checkered text-5xl mb-3"></i>
                    <p class="text-lg">No milestones yet</p>
                    <p class="text-sm">Add your first milestone to track project progress</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Add/Edit Milestone Modal -->
    @if($showMilestoneModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeMilestoneModal"></div>

                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form wire:submit.prevent="saveMilestone">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">
                                {{ $editingMilestoneId ? 'Edit Milestone' : 'Add Milestone' }}
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Name -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Milestone Name *</label>
                                    <input type="text" wire:model="milestone_name" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    @error('milestone_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Description -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                                    <textarea wire:model="milestone_description" rows="3" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <!-- Start Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                                    <input type="date" wire:model="milestone_start_date" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Due Date -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Due Date</label>
                                    <input type="date" wire:model="milestone_due_date" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    @error('milestone_due_date') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Status -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select wire:model="milestone_status" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
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
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Priority</label>
                                    <select wire:model="milestone_priority" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                        <option value="critical">Critical</option>
                                    </select>
                                </div>

                                <!-- Progress -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Progress (%)</label>
                                    <input type="number" min="0" max="100" wire:model="milestone_progress" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                </div>

                                <!-- Amount -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Amount (UGX)</label>
                                    <input type="number" step="0.01" wire:model="milestone_amount" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="Optional">
                                </div>

                                <!-- Percentage -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Budget % (optional)</label>
                                    <input type="number" step="0.01" min="0" max="100" wire:model="milestone_percentage" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="% of project budget">
                                </div>

                                <!-- Is Billable -->
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" wire:model="milestone_is_billable" 
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                    <label class="text-sm font-medium text-gray-700">Is Billable</label>
                                </div>

                                <!-- Assigned To -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Assigned To</label>
                                    <select wire:model="milestone_assigned_to" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select User</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Depends On -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Depends On</label>
                                    <select wire:model="milestone_depends_on" class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="">None</option>
                                        @foreach($availableMilestones as $ms)
                                            <option value="{{ $ms->id }}">{{ $ms->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Deliverables -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Deliverables</label>
                                    <textarea wire:model="milestone_deliverables" rows="3" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="List expected deliverables..."></textarea>
                                </div>

                                <!-- Notes -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                                    <textarea wire:model="milestone_notes" rows="2" 
                                        class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                                {{ $editingMilestoneId ? 'Update' : 'Create' }} Milestone
                            </button>
                            <button type="button" wire:click="closeMilestoneModal" 
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
