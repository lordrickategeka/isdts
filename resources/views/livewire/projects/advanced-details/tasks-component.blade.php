<div>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Project Tasks</h2>
            <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition">
                <i class="fas fa-plus mr-2"></i>Add Task
            </button>
        </div>

        <!-- Tasks List -->
        <div class="space-y-4">
            @forelse($tasks as $task)
                <div class="border rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-lg font-medium text-gray-900">{{ $task->name }}</h3>
                            @if($task->description)
                                <p class="text-sm text-gray-600 mt-2">{{ $task->description }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-tasks text-5xl mb-3"></i>
                    <p class="text-lg">No tasks yet</p>
                    <p class="text-sm">Add tasks to break down project work</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
