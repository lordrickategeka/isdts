<div>
    @if($submitted)
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
            <p class="font-medium">{{ $form->settings['success_message'] ?? 'Form submitted successfully!' }}</p>
        </div>
    @else
        <form wire:submit.prevent="submit" class="space-y-6">
            @foreach($form->fields as $field)
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

                        @case('file')
                            <input
                                type="file"
                                id="{{ $field->name }}"
                                wire:model="formData.{{ $field->name }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            >
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

            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 py-3 px-6 rounded-lg font-medium transition-colors">
                    {{ $form->settings['submit_button_text'] ?? 'Submit' }}
                </button>
            </div>
        </form>
    @endif
</div>
