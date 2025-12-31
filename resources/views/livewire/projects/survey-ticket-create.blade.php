<div class="min-h-screen bg-gray-50">
    <div class="bg-base-100 border-b border-gray-200 print:hidden">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-1xl font-bold text-black">Create Survey Ticket</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="text-1xl text-gray-600 hover:text-gray-800">
                ← Back to Dashboard
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-lg shadow-md p-6">
                @if (session()->has('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                        {{ session('success') }}
                        @if (session()->has('survey_links'))
                            <div class="mt-2">
                                <strong>Survey Links for Engineers:</strong>
                                <ul class="list-disc ml-6">
                                    @foreach (session('survey_links') as $link)
                                        <li><a href="{{ $link }}" class="text-blue-600 underline" target="_blank">{{ $link }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-800 rounded">
                        <strong>There were errors with your submission:</strong>
                        <ul class="list-disc ml-6">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form wire:submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Survey Name</label>
                            <input type="text" wire:model="survey_name" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" wire:model="start_date" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Assign Engineers</label>
                            <select wire:model="engineer_user_ids" multiple class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                @foreach ($engineers as $engineer)
                                    <option value="{{ $engineer->id }}">{{ $engineer->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-xs text-gray-500">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Project</label>
                            <select wire:model="project_id" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Select Project</option>
                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Client</label>
                            <select wire:model="client_id" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Select Client</option>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Contact Person (Client Side)</label>
                            <input type="text" wire:model="contact_person" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Description</label>
                            <textarea wire:model="description" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg"></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Location</label>
                            <input type="text" wire:model="location" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Priority</label>
                            <select wire:model="priority" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Select Priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model="status" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Assigned User</label>
                            <select wire:model="assigned_user_id" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                <option value="">Select Assignee</option>
                                @foreach ($assignees as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Is this a Project Survey?</label>
                            <select wire:model="is_project_survey" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg" required>
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-medium disabled:opacity-50 flex items-center gap-2">
                            <span>Create Survey Ticket</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
