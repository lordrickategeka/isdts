<div class="bg-white rounded-lg shadow-md p-6" wire:loading.class="opacity-50">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Form Fields</h2>

        <button type="button" wire:click="$set('showFieldModal', true)"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg inline-flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4v16m8-8H4"></path>
            </svg>
            Add Field
        </button>
    </div>

    @if (count($fields) > 0)
        <div class="space-y-4">

            @foreach ($fields as $index => $field)
                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50"
                    wire:key="field-{{ $index }}-{{ $field['field_type'] }}">

                    <!-- Field Header -->
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-sm font-medium text-gray-600">Field #{{ $index + 1 }}</span>

                        <div class="flex gap-2">
                            @if ($index > 0)
                                <button type="button" wire:click="moveFieldUp({{ $index }})"
                                    class="p-1 text-gray-600 hover:text-gray-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M5 15l7-7 7 7"></path>
                                    </svg>
                                </button>
                            @endif

                            @if ($index < count($fields) - 1)
                                <button type="button" wire:click="moveFieldDown({{ $index }})"
                                    class="p-1 text-gray-600 hover:text-gray-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            @endif

                            <button type="button" wire:click="removeField({{ $index }})"
                                class="p-1 text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Field Input Area -->
                    <div class="grid grid-cols-2 gap-4">

                        {{-- Field Type --}}
                        <div class="relative">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Type *</label>
                            <select wire:model="fields.{{ $index }}.field_type"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                @foreach ($fieldTypes as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>

                            <div wire:loading wire:target="fields.{{ $index }}.field_type"
                                class="absolute right-2 top-8">
                                <svg class="animate-spin h-5 w-5 text-blue-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </div>
                        </div>

                        {{-- Field Label --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Label *</label>
                            <input type="text" wire:model="fields.{{ $index }}.label"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g., Full Name">
                            @error("fields.$index.label")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Field Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Name *</label>
                            <input type="text" wire:model="fields.{{ $index }}.name"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="e.g., full_name">
                            @error("fields.$index.name")
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- Placeholder --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Placeholder</label>
                            <input type="text" wire:model="fields.{{ $index }}.placeholder"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Enter placeholder text">
                        </div>

                        {{-- Help Text --}}
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Help Text</label>
                            <input type="text" wire:model="fields.{{ $index }}.help_text"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="Additional help or instructions">
                        </div>

                        {{-- Options Section --}}
                        @if (in_array($field['field_type'], ['select', 'radio', 'checkbox']))
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Options (one per line)
                                </label>

                                <div class="flex gap-2">
                                    <textarea wire:model="fields.{{ $index }}.options" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                        placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>

                                    <button type="button"
                                        class="ml-2 px-3 py-2 bg-blue-500 text-white rounded hover:bg-blue-600"
                                        wire:click="addOption({{ $index }})">
                                        Add Option
                                    </button>
                                </div>

                                <small class="text-gray-500">Each line becomes a selectable option.</small>
                            </div>
                        @endif

                        {{-- Required Toggle --}}
                        <div class="col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" wire:model="fields.{{ $index }}.is_required"
                                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200">
                                <span class="ml-2 text-sm text-gray-700">This field is required</span>
                            </label>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @else
        <div class="text-center py-8 text-gray-500">
            <p>No fields added yet. Click "Add Field" to get started.</p>
        </div>
    @endif
</div>
