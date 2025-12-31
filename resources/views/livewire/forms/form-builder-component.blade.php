<div>
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4 max-w-7xl mx-auto">
            <div>
                <h1 class="text-2xl font-bold text-black">
                    {{ $formId ? 'Edit Form' : 'Create New Form' }}
                </h1>
                <p class="text-sm text-gray-500">Design your custom form</p>
            </div>
            <a href="{{ route('forms.index') }}" class="text-gray-600 hover:text-gray-800">
                ← Back to Forms
            </a>
        </div>
    </div>

    <!-- PAGE BODY -->
    <div class="py-8 px-2 sm:px-4 bg-gray-50 min-h-screen">
        <form wire:submit.prevent="save">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- =======================
                     COLUMN 1: FORM BUILDER
                ========================== -->
                <div class="space-y-6 lg:col-span-1">
                    <!-- Form Details -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Form Details</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Form Name *</label>
                                <input type="text" wire:model="name"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="Enter form name">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea wire:model="description" rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                          placeholder="Brief description"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    @include('livewire.forms.add-fields')

                    {{-- @include('livewire.form-builder.surveyjs-builder') --}}
                    <!-- (Keep your long fields code the same; no change needed except grouping it here) -->
                </div>

                <!-- =======================
                     COLUMN 2: PREVIEW
                ========================== -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                        <h2 class="text-xl font-semibold mb-4">Preview</h2>

                        <div class="mb-4">
                            <h3 class="text-lg font-bold">{{ $name ?: 'Form Name' }}</h3>
                            <p class="text-gray-500">{{ $description ?: 'Form description...' }}</p>
                        </div>

                        @if (count($fields))
                            <form class="space-y-4">
                                @foreach ($fields as $index => $field)
                                    <div>
                                        <label class="block text-gray-700 font-medium mb-1">
                                            {{ $field['label'] ?? 'Label' }}
                                            @if ($field['is_required'] ?? false)
                                                <span class="text-red-500">*</span>
                                            @endif
                                        </label>

                                        <!-- Your preview component logic unchanged -->
                                        @include('livewire.forms.preview-field', ['field' => $field])
                                    </div>
                                @endforeach
                            </form>
                        @else
                            <p class="text-gray-400">No fields to preview yet.</p>
                        @endif
                    </div>
                </div>

                <!-- =======================
                     COLUMN 3: SETTINGS
                ========================== -->
                <div>
                    <div class="bg-white rounded-lg shadow-md p-6 sticky top-6 space-y-4">
                        <h2 class="text-xl font-semibold mb-4">Settings</h2>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select wire:model="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option value="draft">Draft</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Submit Button Text</label>
                            <input type="text" wire:model="submitButtonText"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Success Message</label>
                            <textarea wire:model="successMessage" rows="3"
                                      class="w-full px-3 py-2 border border-blue-500 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="pt-4 border-t">
                            <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 py-2 px-4 rounded-lg font-medium text-white">
                                Save Form
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <!-- Add Field Modal -->
<div
    x-data="{ open: @entangle('showFieldModal') }"
    x-show="open"
    x-cloak
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
>
    <div
        @click.away="open = false"
        class="bg-white w-full max-w-lg rounded-lg shadow-xl p-6"
    >
        <h2 class="text-xl font-semibold mb-4">Add Field</h2>

        <!-- Field Type -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Field Type *
            </label>
            <select
                wire:model="newField.field_type"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >
                <option value="">Select field type</option>
                @foreach ($fieldTypes as $key => $label)
                    <option value="{{ $key }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('newField.field_type')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Label -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Field Label *
            </label>
            <input
                type="text"
                wire:model="newField.label"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >
            @error('newField.label')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Name -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                Field Name *
            </label>
            <input
                type="text"
                wire:model="newField.name"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >
            @error('newField.name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>

        <!-- Placeholder -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Placeholder</label>
            <input
                type="text"
                wire:model="newField.placeholder"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- Help Text -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Help Text</label>
            <input
                type="text"
                wire:model="newField.help_text"
                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
            >
        </div>

        <!-- Options for select/radio/checkbox -->
        @if (in_array($newField['field_type'] ?? '', ['select', 'radio', 'checkbox']))
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Options (one per line)
                </label>
                <textarea
                    wire:model="newField.options"
                    rows="3"
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                    placeholder="Option 1&#10;Option 2&#10;Option 3"
                ></textarea>
                <small class="text-gray-500">Each line will become an option.</small>
            </div>
        @endif

        <!-- Required -->
        <div class="mb-4">
            <label class="inline-flex items-center text-sm">
                <input
                    type="checkbox"
                    wire:model="newField.is_required"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                >
                <span class="ml-2">Required</span>
            </label>
        </div>

        <!-- Footer -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button
                type="button"
                @click="open = false"
                class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300"
            >
                Cancel
            </button>

            <button
                type="button"
                wire:click="saveNewField"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700"
            >
                Save Field
            </button>
        </div>
    </div>
</div>

</div>
