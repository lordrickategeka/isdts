<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-base-100 border-b border-gray-200">
        <div class="flex items-center justify-between p-4">
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

    <!-- Main Content -->
    <div class="p-6">
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form Builder -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Form Details Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold mb-4">Form Details</h2>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Form Name *</label>
                            <input type="text"
                                wire:model="name"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Enter form name">
                            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea
                                wire:model="description"
                                rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Brief description of the form"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Form Fields Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Form Fields</h2>
                        <button type="button"
                            wire:click="addField"
                            class="bg-blue-600 hover:bg-blue-700  px-4 py-2 rounded-lg inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Field
                        </button>
                    </div>

                    @if(count($fields) > 0)
                        <div class="space-y-4">
                            @foreach($fields as $index => $field)
                                <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="text-sm font-medium text-gray-600">Field #{{ $index + 1 }}</span>
                                        <div class="flex gap-2">
                                            @if($index > 0)
                                                <button type="button"
                                                    wire:click="moveFieldUp({{ $index }})"
                                                    class="p-1 text-gray-600 hover:text-gray-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            @if($index < count($fields) - 1)
                                                <button type="button"
                                                    wire:click="moveFieldDown({{ $index }})"
                                                    class="p-1 text-gray-600 hover:text-gray-800">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                            <button type="button"
                                                wire:click="removeField({{ $index }})"
                                                class="p-1 text-red-600 hover:text-red-800">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Type *</label>
                                            <select wire:model="fields.{{ $index }}.field_type"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                                @foreach($fieldTypes as $key => $label)
                                                    <option value="{{ $key }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Label *</label>
                                            <input type="text"
                                                wire:model="fields.{{ $index }}.label"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                placeholder="e.g., Full Name">
                                            @error("fields.$index.label") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Field Name *</label>
                                            <input type="text"
                                                wire:model="fields.{{ $index }}.name"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                placeholder="e.g., full_name">
                                            @error("fields.$index.name") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Placeholder</label>
                                            <input type="text"
                                                wire:model="fields.{{ $index }}.placeholder"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                placeholder="Enter placeholder text">
                                        </div>

                                        <div class="col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Help Text</label>
                                            <input type="text"
                                                wire:model="fields.{{ $index }}.help_text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                placeholder="Additional help or instructions">
                                        </div>

                                        @if(in_array($field['field_type'], ['select', 'radio', 'checkbox']))
                                            <div class="col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Options (one per line)</label>
                                                <textarea
                                                    wire:model="fields.{{ $index }}.options"
                                                    rows="3"
                                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                                    placeholder="Option 1&#10;Option 2&#10;Option 3"></textarea>
                                            </div>
                                        @endif

                                        <div class="col-span-2">
                                            <label class="flex items-center">
                                                <input type="checkbox"
                                                    wire:model="fields.{{ $index }}.is_required"
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
            </div>

            <!-- Settings Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-6">
                    <h2 class="text-xl font-semibold mb-4">Settings</h2>

                    <div class="space-y-4">
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
                            <input type="text"
                                wire:model="submitButtonText"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Success Message</label>
                            <textarea
                                wire:model="successMessage"
                                rows="3"
                                class="w-full px-3 py-2 border border-blue-500 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <div class="pt-4 border-t">
                            <button type="submit"
                                class="w-full bg-blue-600 hover:bg-blue-700  py-2 px-4 rounded-lg font-medium">
                                Save Form
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    </div>
</div>
