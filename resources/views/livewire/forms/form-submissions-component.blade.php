<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Form Submissions</h1>
                <p class="text-sm text-gray-500">{{ $form->name }}</p>
            </div>
            <a href="{{ route('forms.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Back to Forms
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if($submissions->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                ID
                            </th>
                            @foreach($form->fields as $field)
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    {{ $field->label }}
                                </th>
                            @endforeach
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Submitted At
                            </th>
                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach($submissions as $index => $submission)
                            <tr class="{{ $index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150">
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-900">
                                    #{{ $submission->id }}
                                </td>
                                @foreach($form->fields as $field)
                                    <td class="px-4 py-2 text-xs text-gray-900">
                                        @php
                                            $value = $submission->data[$field->name] ?? 'N/A';
                                            if (is_array($value)) {
                                                echo implode(', ', $value);
                                            } else {
                                                echo $value;
                                            }
                                        @endphp
                                    </td>
                                @endforeach
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                    {{ $submission->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-4 py-2 whitespace-nowrap text-xs text-gray-500">
                                    <button
                                        wire:click="deleteSubmission({{ $submission->id }})"
                                        wire:confirm="Are you sure you want to delete this submission?"
                                        class="text-red-600 hover:text-red-800">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {{ $submissions->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions yet</h3>
                <p class="mt-1 text-sm text-gray-500">This form hasn't received any submissions.</p>
            </div>
        @endif
    </div>
    </div>
</div>
