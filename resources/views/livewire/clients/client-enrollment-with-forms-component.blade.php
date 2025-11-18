<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
            <div>
                <h1 class="text-2xl font-bold text-black">Enroll New Client</h1>
                <p class="text-sm text-gray-500">Step {{ $currentStep }} of {{ $totalSteps }}</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-800">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="p-6">
        <div class="max-w-4xl mx-auto">
            @if (session()->has('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    @for ($i = 1; $i <= $totalSteps; $i++)
                        <div class="flex-1 {{ $i < $totalSteps ? 'mr-2' : '' }}">
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full
                                    {{ $currentStep >= $i ? 'bg-blue-600 text-white' : 'bg-gray-300 text-gray-600' }}
                                    font-semibold">
                                    {{ $i }}
                                </div>
                                @if ($i < $totalSteps)
                                    <div class="flex-1 h-1 mx-2
                                        {{ $currentStep > $i ? 'bg-blue-600' : 'bg-gray-300' }}">
                                    </div>
                                @endif
                            </div>
                            <div class="text-xs mt-2 text-center {{ $currentStep >= $i ? 'text-blue-600 font-semibold' : 'text-gray-500' }}">
                                Step {{ $i }}
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form wire:submit.prevent="{{ $currentStep == $totalSteps ? 'submit' : 'nextStep' }}">
                    @php
                        $currentForm = null;
                        if ($currentStep == 1 && $step1Form) $currentForm = $step1Form;
                        elseif ($currentStep == 2 && $step2Form) $currentForm = $step2Form;
                        elseif ($currentStep == 3 && $step3Form) $currentForm = $step3Form;
                    @endphp

                    @if($currentForm)
                        <div class="space-y-6">
                            <h2 class="text-xl font-semibold text-gray-800 mb-4">
                                {{ $currentForm->name }}
                            </h2>

                            @if($currentForm->description)
                                <p class="text-gray-600 mb-6">{{ $currentForm->description }}</p>
                            @endif

                            <!-- Render Form Fields -->
                            @foreach($currentForm->fields as $field)
                                <div>
                                    <label for="{{ $field->name }}" class="block text-sm font-medium text-gray-700 mb-1">
                                        {{ $field->label }}
                                        @if($field->is_required)
                                            <span class="text-red-500">*</span>
                                        @endif
                                    </label>

                                    @switch($field->field_type)
                                        @case('textarea')
                                            <textarea
                                                id="{{ $field->name }}"
                                                wire:model="formData.{{ $field->name }}"
                                                placeholder="{{ $field->placeholder }}"
                                                rows="4"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            ></textarea>
                                            @break

                                        @case('select')
                                            <select
                                                id="{{ $field->name }}"
                                                wire:model="formData.{{ $field->name }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                                <option value="">Select an option</option>
                                                @if(is_array($field->options))
                                                    @foreach($field->options as $option)
                                                        <option value="{{ $option }}">{{ $option }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @break

                                        @case('radio')
                                            <div class="space-y-2">
                                                @if(is_array($field->options))
                                                    @foreach($field->options as $option)
                                                        <label class="flex items-center">
                                                            <input
                                                                type="radio"
                                                                wire:model="formData.{{ $field->name }}"
                                                                value="{{ $option }}"
                                                                class="rounded-full border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                                            >
                                                            <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @break

                                        @case('checkbox')
                                            <div class="space-y-2">
                                                @if(is_array($field->options))
                                                    @foreach($field->options as $option)
                                                        <label class="flex items-center">
                                                            <input
                                                                type="checkbox"
                                                                wire:model="formData.{{ $field->name }}"
                                                                value="{{ $option }}"
                                                                class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200"
                                                            >
                                                            <span class="ml-2 text-sm text-gray-700">{{ $option }}</span>
                                                        </label>
                                                    @endforeach
                                                @endif
                                            </div>
                                            @break

                                        @default
                                            <input
                                                type="{{ $field->field_type }}"
                                                id="{{ $field->name }}"
                                                wire:model="formData.{{ $field->name }}"
                                                placeholder="{{ $field->placeholder }}"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            >
                                    @endswitch

                                    @if($field->help_text)
                                        <p class="mt-1 text-sm text-gray-500">{{ $field->help_text }}</p>
                                    @endif

                                    @error("formData.{$field->name}")
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            <p>No form configured for this step.</p>
                        </div>
                    @endif

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <button type="button"
                            wire:click="previousStep"
                            @if($currentStep == 1) disabled @endif
                            class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>

                        <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium">
                            @if ($currentStep == $totalSteps)
                                Submit & Enroll
                            @else
                                Next Step
                            @endif
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
