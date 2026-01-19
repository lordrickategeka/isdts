<div>
    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('message') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Project Tasks</h2>
            <button wire:click="openAddTaskModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus mr-2"></i>Add Task
            </button>
        </div>

        <!-- Tasks Table -->
        <div class="overflow-x-auto">
            @if($tasks->count() > 0)
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Task Name
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Priority
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Assigned To
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Milestone
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Due Date
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Est. Hours
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($tasks as $task)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $task->name }}</div>
                                        @if($task->description)
                                            <div class="text-xs text-gray-500 mt-1 line-clamp-2">{{ $task->description }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($task->status === 'todo') bg-gray-100 text-gray-800
                                        @elseif($task->status === 'in_progress') bg-blue-100 text-blue-800
                                        @elseif($task->status === 'review') bg-yellow-100 text-yellow-800
                                        @elseif($task->status === 'completed') bg-green-100 text-green-800
                                        @elseif($task->status === 'blocked') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full
                                        @if($task->priority === 'low') bg-gray-100 text-gray-800
                                        @elseif($task->priority === 'medium') bg-blue-100 text-blue-800
                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800
                                        @elseif($task->priority === 'critical') bg-red-100 text-red-800
                                        @endif">
                                        {{ ucfirst($task->priority) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $task->assignedUser->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $task->milestone->name ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap">
                                    {{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900">
                                    {{ $task->estimated_hours ? $task->estimated_hours . 'h' : '-' }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="editTask({{ $task->id }})"
                                            class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 px-2 py-1 rounded transition"
                                            title="Edit Task">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button wire:click="deleteTask({{ $task->id }})"
                                            onclick="return confirm('Are you sure you want to delete this task?')"
                                            class="text-red-600 hover:text-red-900 hover:bg-red-50 px-2 py-1 rounded transition"
                                            title="Delete Task">
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
                    <i class="fas fa-tasks text-5xl mb-3"></i>
                    <p class="text-lg">No tasks yet</p>
                    <p class="text-sm">Add tasks to break down project work</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Task Modal -->
    @if($showTaskModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" wire:click="closeTaskModal">
            <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white" wire:click.stop>
                <!-- Modal Header -->
                <div class="flex justify-between items-center mb-4 pb-3 border-b">
                    <h3 class="text-xl font-semibold text-gray-900">
                        {{ $editingTaskId ? 'Edit Task' : 'Add New Task' }}
                    </h3>
                    <button wire:click="closeTaskModal" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <!-- Modal Body -->
                <form wire:submit.prevent="saveTask">
                    <div class="space-y-4 max-h-[70vh] overflow-y-auto px-1">
                        <!-- Task Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Task Name *</label>
                            <input type="text" wire:model="task_name"
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_name') border-red-500 @enderror">
                            @error('task_name') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="task_description" rows="3"
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_description') border-red-500 @enderror"></textarea>
                            @error('task_description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Row 1: Milestone, Status, Priority -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Milestone</label>
                                <select wire:model="task_milestone_id"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select Milestone</option>
                                    @foreach($milestones as $milestone)
                                        <option value="{{ $milestone->id }}">{{ $milestone->name }}</option>
                                    @endforeach
                                </select>
                                @error('task_milestone_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                                <select wire:model="task_status"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_status') border-red-500 @enderror">
                                    <option value="todo">To Do</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="review">Review</option>
                                    <option value="completed">Completed</option>
                                    <option value="blocked">Blocked</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>
                                @error('task_status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                                <select wire:model="task_priority"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_priority') border-red-500 @enderror">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                                @error('task_priority') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Row 2: Start Date, Due Date, Estimated Hours -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="date" wire:model="task_start_date"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_start_date') border-red-500 @enderror">
                                @error('task_start_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                <input type="date" wire:model="task_due_date"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_due_date') border-red-500 @enderror">
                                @error('task_due_date') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Hours</label>
                                <input type="number" wire:model="task_estimated_hours" min="1"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_estimated_hours') border-red-500 @enderror">
                                @error('task_estimated_hours') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Row 3: Task Type, Assigned To, Depends On -->
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Task Type</label>
                                <input type="text" wire:model="task_task_type" placeholder="e.g., Development, Testing"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_task_type') border-red-500 @enderror">
                                @error('task_task_type') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Assigned To</label>
                                <select wire:model="task_assigned_to"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('task_assigned_to') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Depends On Task</label>
                                <select wire:model="task_depends_on_task_id"
                                    class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">No Dependency</option>
                                    @foreach($availableTasks as $availableTask)
                                        @if($availableTask->id !== $editingTaskId)
                                            <option value="{{ $availableTask->id }}">{{ $availableTask->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('task_depends_on_task_id') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Acceptance Criteria -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Acceptance Criteria</label>
                            <textarea wire:model="task_acceptance_criteria" rows="3"
                                placeholder="Define what needs to be completed for this task to be considered done"
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_acceptance_criteria') border-red-500 @enderror"></textarea>
                            @error('task_acceptance_criteria') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea wire:model="task_notes" rows="2"
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_notes') border-red-500 @enderror"></textarea>
                            @error('task_notes') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                            <input type="text" wire:model="task_tags" placeholder="Comma-separated tags"
                                class="w-full px-3 py-2 text-sm border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('task_tags') border-red-500 @enderror">
                            @error('task_tags') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t">
                        <button type="button" wire:click="closeTaskModal"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            {{ $editingTaskId ? 'Update Task' : 'Create Task' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
