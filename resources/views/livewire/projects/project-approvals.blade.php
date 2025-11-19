<div>
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">{{ $project->name }}</h1>
                    <p class="text-gray-600">{{ $project->project_code }} - Budget Approval</p>
                </div>
                <span class="px-4 py-2 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">
                    {{ ucwords(str_replace('_', ' ', $project->status)) }}
                </span>
            </div>

            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Project Summary -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4">Project Summary</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Start Date</p>
                        <p class="text-lg font-semibold">{{ $project->start_date->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Client</p>
                        <p class="text-lg font-semibold">{{ $project->client?->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Budget</p>
                        <p class="text-lg font-semibold text-blue-600">${{ number_format($totalBudget, 2) }}</p>
                    </div>
                </div>

                @if($project->description)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Description</p>
                        <p class="text-gray-800">{{ $project->description }}</p>
                    </div>
                @endif
            </div>

            <!-- Budget Items -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4">Budget Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($budgetItems as $item)
                                <tr>
                                    <td class="px-4 py-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->item_name }}</div>
                                        @if($item->description)
                                            <div class="text-sm text-gray-500">{{ $item->description }}</div>
                                        @endif
                                        @if($item->justification)
                                            <div class="text-xs text-gray-400 italic mt-1">{{ $item->justification }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->category ? ucfirst($item->category) : 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->quantity }} {{ $item->unit }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-900">${{ number_format($item->unit_cost, 2) }}</td>
                                    <td class="px-4 py-3 text-sm font-semibold text-gray-900">${{ number_format($item->total_cost, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-50 font-bold">
                                <td colspan="4" class="px-4 py-3 text-right">Grand Total:</td>
                                <td class="px-4 py-3 text-lg text-blue-600">${{ number_format($totalBudget, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Approval Status -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-semibold mb-4">Approval Status</h3>

                <div class="space-y-4">
                    @foreach(['cto' => 'CTO', 'director' => 'Director'] as $role => $label)
                        @php
                            $approval = $approvals->firstWhere('approver_role', $role);
                        @endphp

                        <div class="flex items-center justify-between p-4 border rounded-lg
                            @if($approval && $approval->status === 'approved') bg-green-50 border-green-300
                            @elseif($approval && $approval->status === 'rejected') bg-red-50 border-red-300
                            @else bg-gray-50 border-gray-300
                            @endif">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center
                                    @if($approval && $approval->status === 'approved') bg-green-500
                                    @elseif($approval && $approval->status === 'rejected') bg-red-500
                                    @else bg-gray-400
                                    @endif">
                                    @if($approval && $approval->status === 'approved')
                                        <i class="fas fa-check text-white"></i>
                                    @elseif($approval && $approval->status === 'rejected')
                                        <i class="fas fa-times text-white"></i>
                                    @else
                                        <i class="fas fa-clock text-white"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $label }}</p>
                                    @if($approval)
                                        <p class="text-sm text-gray-600">
                                            {{ $approval->approver->name }} -
                                            {{ $approval->reviewed_at->format('M d, Y H:i') }}
                                        </p>
                                        @if($approval->comments)
                                            <p class="text-sm text-gray-700 mt-1 italic">"{{ $approval->comments }}"</p>
                                        @endif
                                    @else
                                        <p class="text-sm text-gray-600">Pending approval</p>
                                    @endif
                                </div>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($approval && $approval->status === 'approved') bg-green-200 text-green-800
                                @elseif($approval && $approval->status === 'rejected') bg-red-200 text-red-800
                                @else bg-gray-200 text-gray-800
                                @endif">
                                {{ $approval ? ucfirst($approval->status) : 'Pending' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Approval Actions (for CTO/Director) -->
            @if($userRole)
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h3 class="text-xl font-semibold mb-4">Your Approval Decision</h3>

                    @if($existingApproval && $existingApproval->status !== 'pending')
                        <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg mb-4">
                            <p class="text-blue-800">
                                <i class="fas fa-info-circle mr-2"></i>
                                You have already {{ $existingApproval->status }} this project on {{ $existingApproval->reviewed_at->format('M d, Y H:i') }}.
                            </p>
                        </div>
                    @endif

                    <form>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                            <textarea wire:model="comments" rows="3"
                                      class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                      placeholder="Add any comments or feedback about this project budget..."
                                      @if($existingApproval && $existingApproval->status !== 'pending') readonly @endif></textarea>
                            @error('comments') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        @if(!$existingApproval || $existingApproval->status === 'pending')
                            <div class="flex space-x-3">
                                <button type="button" wire:click="approve"
                                        wire:confirm="Are you sure you want to approve this project?"
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-check mr-2"></i>Approve
                                </button>
                                <button type="button" wire:click="reject"
                                        wire:confirm="Are you sure you want to reject this project?"
                                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg transition duration-200">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                    <p class="text-yellow-800">
                        <i class="fas fa-lock mr-2"></i>
                        Only CTO and Director can approve projects.
                    </p>
                </div>
            @endif

            <!-- Navigation -->
            <div class="flex justify-between items-center">
                <a href="{{ route('projects.list') }}" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Projects
                </a>
            </div>
        </div>
    </div>
</div>
