<?php

namespace App\Livewire\Projects\AdvancedDetails;

use Livewire\Component;
use App\Models\ProjectTask;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TasksComponent extends Component
{
    public $projectId;
    public $tasks = [];
    public $milestones = [];
    public $users = [];
    public $availableTasks = []; // For dependency selection

    // Task modal properties
    public $showTaskModal = false;
    public $editingTaskId = null;
    public $task_name = '';
    public $task_description = '';
    public $task_milestone_id = null;
    public $task_start_date = '';
    public $task_due_date = '';
    public $task_estimated_hours = '';
    public $task_status = 'todo';
    public $task_priority = 'medium';
    public $task_task_type = '';
    public $task_assigned_to = null;
    public $task_depends_on_task_id = null;
    public $task_acceptance_criteria = '';
    public $task_notes = '';
    public $task_tags = '';

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadTasks();
        $this->loadMilestones();
        $this->loadUsers();
    }

    public function loadTasks()
    {
        $this->tasks = ProjectTask::where('project_id', $this->projectId)
            ->with(['assignedUser', 'milestone', 'creator', 'dependsOnTask'])
            ->orderBy('due_date')
            ->get();

        $this->availableTasks = $this->tasks;
    }

    public function loadMilestones()
    {
        $this->milestones = ProjectMilestone::where('project_id', $this->projectId)
            ->orderBy('due_date')
            ->get();
    }

    public function loadUsers()
    {
        $this->users = User::orderBy('name')->get();
    }

    public function openAddTaskModal()
    {
        $this->resetTaskForm();
        $this->showTaskModal = true;
    }

    public function closeTaskModal()
    {
        $this->showTaskModal = false;
        $this->resetTaskForm();
        $this->resetValidation();
    }

    public function resetTaskForm()
    {
        $this->editingTaskId = null;
        $this->task_name = '';
        $this->task_description = '';
        $this->task_milestone_id = null;
        $this->task_start_date = '';
        $this->task_due_date = '';
        $this->task_estimated_hours = '';
        $this->task_status = 'todo';
        $this->task_priority = 'medium';
        $this->task_task_type = '';
        $this->task_assigned_to = null;
        $this->task_depends_on_task_id = null;
        $this->task_acceptance_criteria = '';
        $this->task_notes = '';
        $this->task_tags = '';
    }

    public function saveTask()
    {
        // Convert empty strings to null
        $this->task_milestone_id = $this->task_milestone_id === '' ? null : $this->task_milestone_id;
        $this->task_assigned_to = $this->task_assigned_to === '' ? null : $this->task_assigned_to;
        $this->task_depends_on_task_id = $this->task_depends_on_task_id === '' ? null : $this->task_depends_on_task_id;
        $this->task_estimated_hours = $this->task_estimated_hours === '' ? null : $this->task_estimated_hours;

        $validated = $this->validate([
            'task_name' => 'required|string|max:255',
            'task_description' => 'nullable|string',
            'task_milestone_id' => 'nullable|exists:project_milestones,id',
            'task_start_date' => 'nullable|date',
            'task_due_date' => 'nullable|date|after_or_equal:task_start_date',
            'task_estimated_hours' => 'nullable|integer|min:1',
            'task_status' => 'required|in:todo,in_progress,review,completed,blocked,cancelled',
            'task_priority' => 'required|in:low,medium,high,critical',
            'task_task_type' => 'nullable|string|max:255',
            'task_assigned_to' => 'nullable|exists:users,id',
            'task_depends_on_task_id' => 'nullable|exists:project_tasks,id',
            'task_acceptance_criteria' => 'nullable|string',
            'task_notes' => 'nullable|string',
            'task_tags' => 'nullable|string',
        ]);

        $taskData = [
            'project_id' => $this->projectId,
            'name' => $this->task_name,
            'description' => $this->task_description,
            'milestone_id' => $this->task_milestone_id,
            'start_date' => $this->task_start_date,
            'due_date' => $this->task_due_date,
            'estimated_hours' => $this->task_estimated_hours,
            'status' => $this->task_status,
            'priority' => $this->task_priority,
            'task_type' => $this->task_task_type,
            'assigned_to' => $this->task_assigned_to,
            'depends_on_task_id' => $this->task_depends_on_task_id,
            'acceptance_criteria' => $this->task_acceptance_criteria,
            'notes' => $this->task_notes,
            'tags' => $this->task_tags,
        ];

        if ($this->editingTaskId) {
            $task = ProjectTask::findOrFail($this->editingTaskId);
            $task->update($taskData);
            session()->flash('message', 'Task updated successfully.');
        } else {
            $taskData['created_by'] = Auth::id();
            ProjectTask::create($taskData);
            session()->flash('message', 'Task created successfully.');
        }

        $this->closeTaskModal();
        $this->loadTasks();
    }

    public function editTask($taskId)
    {
        $task = ProjectTask::findOrFail($taskId);

        $this->editingTaskId = $task->id;
        $this->task_name = $task->name;
        $this->task_description = $task->description;
        $this->task_milestone_id = $task->milestone_id;
        $this->task_start_date = $task->start_date?->format('Y-m-d');
        $this->task_due_date = $task->due_date?->format('Y-m-d');
        $this->task_estimated_hours = $task->estimated_hours;
        $this->task_status = $task->status;
        $this->task_priority = $task->priority;
        $this->task_task_type = $task->task_type;
        $this->task_assigned_to = $task->assigned_to;
        $this->task_depends_on_task_id = $task->depends_on_task_id;
        $this->task_acceptance_criteria = $task->acceptance_criteria;
        $this->task_notes = $task->notes;
        $this->task_tags = $task->tags;

        $this->showTaskModal = true;
    }

    public function deleteTask($taskId)
    {
        $task = ProjectTask::findOrFail($taskId);
        $task->delete();

        session()->flash('message', 'Task deleted successfully.');
        $this->loadTasks();
    }

    public function render()
    {
        return view('livewire.projects.advanced-details.tasks-component');
    }
}
