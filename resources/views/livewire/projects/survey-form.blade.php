<div class="min-h-screen bg-gray-50">
    <div class="bg-base-100 border-b border-gray-200 print:hidden">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-1xl font-bold text-black">Technical Site Survey</h1>
                <p class="text-sm text-gray-500">Step {{ $step }} of 5</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-1xl text-gray-600 hover:text-gray-800">
                ← Back to Dashboard
            </a>
        </div>
    </div>
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            <!-- Progress Steps -->
            <div class="mb-8 print:hidden">
                <div class="flex items-center justify-between">
                    @for ($i = 1; $i <= 5; $i++)
                        <div class="flex-1 {{ $i < 5 ? 'mr-2' : '' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full
                                    {{ $step >= $i ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}
                                    font-semibold">
                                    {{ $i }}
                                </div>
                                @if ($i < 5)
                                    <div class="flex-1 h-1 mx-2
                                        {{ $step > $i ? 'bg-blue-600' : 'bg-gray-300' }}">
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs mt-2 text-center {{ $step >= $i ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">
                                @if ($i == 1) Site Info
                                @elseif ($i == 2) Transmission
                                @elseif ($i == 3) Technical
                                @elseif ($i == 4) Comments
                                @else Signatures
                                @endif
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-md p-6">
                <form wire:submit.prevent="@if($step == 5) submit @else nextStep @endif">
                    @if ($step === 1)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Survey Ticket Information</h2>
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
                            </div>
                        </div>
                    @endif
                    @if ($step === 2)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Fiber & Transmission Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Existing Fiber Site</label>
                                    <select wire:model="existing_fiber_site" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                        <option value="">Select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Desired Site (Micro Wave)</label>
                                    <input type="text" wire:model="desired_site" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Near Site Candidate 1</label>
                                    <input type="text" wire:model="near_site_candidate_1" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Near Site Candidate 2</label>
                                    <input type="text" wire:model="near_site_candidate_2" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Near Site Candidate 3</label>
                                    <input type="text" wire:model="near_site_candidate_3" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($step === 3)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Technical Parameters</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Longitude</label>
                                    <input type="text" wire:model="longitude" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Latitude</label>
                                    <input type="text" wire:model="latitude" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Line-of-sight</label>
                                    <select wire:model="line_of_sight" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                        <option value="">Select</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Ticket Number</label>
                                    <input type="text" wire:model="ticket_number" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Site Elevation (mast)</label>
                                    <input type="text" wire:model="site_elevation" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Hop Length (km)</label>
                                    <input type="text" wire:model="hop_length" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Terrain Factor</label>
                                    <input type="text" wire:model="terrain_factor" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Climate Factor (B)</label>
                                    <input type="text" wire:model="climate_factor" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($step === 4)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Comments & Diagram</h2>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Comments and Diagram</label>
                                <textarea wire:model="comments" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg"></textarea>
                            </div>
                        </div>
                    @endif
                    @if ($step === 5)
                        <div class="space-y-4">
                            <h2 class="text-lg font-semibold text-gray-800 mb-3">Representatives & Signatures</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">BCC Representative Name</label>
                                    <input type="text" wire:model="bcc_representative_name" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">BCC Representative Sign</label>
                                    <input type="text" wire:model="bcc_representative_sign" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">BCC Representative Job Title</label>
                                    <input type="text" wire:model="bcc_representative_job_title" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Customer Representative Name</label>
                                    <input type="text" wire:model="customer_representative_name" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Customer Representative Sign</label>
                                    <input type="text" wire:model="customer_representative_sign" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Customer Representative Job Title</label>
                                    <input type="text" wire:model="customer_representative_job_title" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Date</label>
                                    <input type="date" wire:model="survey_date" class="w-full px-3 py-1.5 text-sm border border-gray-300 rounded-lg">
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <button type="button" wire:click="prevStep" @if($step == 1) disabled @endif
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white rounded-lg font-medium disabled:opacity-50 flex items-center gap-2">
                            @if ($step == 5)
                                <span>Submit Survey</span>
                            @else
                                <span>Next Step</span>
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

