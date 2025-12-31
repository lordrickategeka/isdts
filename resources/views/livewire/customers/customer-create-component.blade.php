<div>
    <div class="bg-base-100 border-b border-gray-200">
        <div class="w-full mx-0 px-2 md:px-6">
            <div class="flex items-center justify-between py-4">
                <div>
                    <h1 class="text-2xl font-bold text-black">Customers</h1>
                    <p class="text-sm text-gray-500">Manage all Your Customers.</p>
                </div>
                <a href="{{ route('customers.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Customer
                </a>
            </div>
        </div>
    </div>


    <div class="p-4 sm:p-6">

        @if (session()->has('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                {{ session('message') }}
            </div>
        @endif

        <div class="w-full bg-gray-50">
            <div class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow-md">
                <div class="mb-4">
                    <div class="flex items-center justify-between">
                        <nav class="-mb-px flex space-x-12" aria-label="Tabs">
                            <a href="#" wire:click.prevent="setMode('project')"
                                class="pb-3 px-3 rounded-md font-medium text-sm whitespace-nowrap {{ $mode === 'project' ? 'border-blue-200 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                                Project Clients
                            </a>

                            <a href="#" wire:click.prevent="setMode('customer')"
                                class="pb-3 px-3 rounded-md font-medium text-sm whitespace-nowrap {{ $mode === 'customer' ? 'border-blue-200 text-blue-600 bg-blue-50' : 'border-transparent text-gray-600 hover:text-gray-800' }}">
                                Customers
                            </a>
                        </nav>

                        <div class="ml-6">
                            <button type="button" wire:click.prevent="openImportModal"
                                class="inline-flex items-center gap-2 px-3 py-2 bg-gray-100 hover:bg-gray-200 rounded-md text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1M12 12v8m0-8l3 3m-3-3-3 3M21 8V7a2 2 0 00-2-2H5a2 2 0 00-2 2v1" />
                                </svg>
                                Import
                            </button>
                        </div>
                    </div>
                </div>

                <form wire:submit.prevent="save">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Row 1 -->
                        @if ($mode === 'project')
                            <div>
                                <label class="text-xs font-semibold">Project</label>
                                <select wire:model="project_id" class="w-full mt-1 border rounded px-3 py-2 text-sm">
                                    <option value="">-- Select project --</option>
                                    @foreach ($projects as $proj)
                                        <option value="{{ $proj->id }}">{{ $proj->name }}</option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="text-red-600 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label class="text-xs font-semibold">Client Name</label>
                            <input wire:model.defer="client_name" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('client_name')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Vendor</label>
                            <select wire:model="vendor_id" class="w-full mt-1 border rounded px-3 py-2 text-sm">
                                <option value="">-- Select vendor --</option>
                                @foreach ($vendors as $v)
                                    <option value="{{ $v->id }}">{{ $v->name }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row 2 -->
                        <div>
                            <label class="text-xs font-semibold">Phone</label>
                            <input wire:model.defer="phone" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('phone')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Email</label>
                            <input wire:model.defer="email" type="email"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('email')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Transmission</label>
                            <input wire:model.defer="transmission" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('transmission')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row 3: Region / District / Coordinates -->
                        <div>
                            <label class="text-xs font-semibold">Region</label>
                            <input wire:model.defer="region" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('region')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">District</label>
                            <input wire:model.defer="district" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('district')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Coordinates (Latitude / Longitude)</label>
                            <div class="flex gap-2 mt-1">
                                <input wire:model.defer="latitude" type="text" placeholder="Latitude"
                                    class="w-1/2 border rounded px-3 py-2 text-sm" />
                                <input wire:model.defer="longitude" type="text" placeholder="Longitude"
                                    class="w-1/2 border rounded px-3 py-2 text-sm" />
                            </div>
                            <label class="inline-flex items-center text-sm mt-2">
                                {{-- <input wire:model="coordinates_auto" type="checkbox" class="mr-2" /> Auto (use browser / API) --}}
                            </label>
                            @error('latitude')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                            @error('longitude')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row 4 -->
                        @if ($mode === 'project')
                            <div>
                                <label class="text-xs font-semibold">NRC (Installation Charge)</label>
                                <input wire:model.defer="nrc" type="text"
                                    class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                                @error('nrc')
                                    <div class="text-red-600 text-xs">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="text-xs font-semibold">MRC (Monthly Charge)</label>
                                <input wire:model.defer="mrc" type="number" step="0.01"
                                    class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                                @error('mrc')
                                    <div class="text-red-600 text-xs">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div>
                            <label class="text-xs font-semibold">VLAN</label>
                            <input wire:model.defer="vlan" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('vlan')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row 5 -->
                        <div>
                            <label class="text-xs font-semibold">Capacity</label>
                            <input wire:model.defer="capacity" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('capacity')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Installation Date</label>
                            <input wire:model.defer="installation_date" type="date"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('installation_date')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-semibold">Installation Engineer / Leader</label>
                            <input wire:model.defer="installation_engineer" type="text"
                                class="w-full mt-1 border rounded px-3 py-2 text-sm" />
                            @error('installation_engineer')
                                <div class="text-red-600 text-xs">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Row 6: Status full-width -->
                        <div class="md:col-span-3">
                            <label class="text-xs font-semibold">Status</label>
                            <select wire:model.defer="status" class="w-full mt-1 border rounded px-3 py-2 text-sm">
                                <option value="pending">Pending</option>
                                <option value="active">Active</option>
                                <option value="installed">Installed</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Create
                            Customer</button>
                        <a href="{{ route('customers.index') }}" class="px-4 py-2 border rounded text-sm">Cancel</a>
                    </div>
                </form>

                {{-- Import Modal (MODAL-UI.md pattern) --}}

                @if ($showImportModal)
                    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
                        aria-modal="true">
                        <div
                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                                wire:click="closeImportModal"></div>

                            <div
                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                                <form wire:submit.prevent="importConfirm">
                                    <div class="bg-base-100 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 id="import-modal-title" class="text-lg font-medium text-black">Import
                                                Customers</h3>
                                            <button type="button" wire:click="closeImportModal"
                                                class="text-gray-400 hover:text-gray-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-black"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>

                                        <div class="grid grid-cols-1 gap-4">
                                            <div>
                                                <label class="text-xs font-semibold">Import Mode</label>
                                                <div class="flex items-center gap-4 mt-2">
                                                    <label class="inline-flex items-center gap-2">
                                                        <input type="radio" wire:model="importMode"
                                                            value="project" />
                                                        <span class="text-sm">With Project</span>
                                                    </label>
                                                    <label class="inline-flex items-center gap-2">
                                                        <input type="radio" wire:model="importMode"
                                                            value="customer" />
                                                        <span class="text-sm">Standalone Customers (No Project)</span>
                                                    </label>
                                                </div>
                                            </div>

                                            @if ($importMode === 'project')
                                                <div>
                                                    <label class="text-xs font-semibold">Target Project</label>
                                                    <select wire:model="importProjectId"
                                                        class="w-full mt-1 border rounded px-3 py-2 text-sm">
                                                        <option value="">-- Select project --</option>
                                                        @foreach ($projects as $p)
                                                            <option value="{{ $p->id }}">{{ $p->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endif

                                            <div>
                                                <label class="text-xs font-semibold">File (CSV)</label>
                                                <input type="file" wire:model="importFile" class="mt-2" />
                                                <div class="mt-2 text-xs text-gray-500">
                                                    <span wire:loading wire:target="importFile">Uploading…</span>
                                                </div>
                                                @error('importFile')
                                                    <div class="text-red-600 text-xs">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                                        <button type="submit" wire:loading.attr="disabled"
                                            wire:target="importConfirm,importFile"
                                            class="w-full sm:w-auto px-4 py-2 bg-primary text-white rounded-lg inline-flex items-center gap-2">
                                            <svg wire:loading wire:target="importConfirm"
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="animate-spin w-4 h-4 text-white" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z">
                                                </path>
                                            </svg>
                                            <span>Start Import</span>
                                        </button>
                                        <button type="button" wire:click="closeImportModal"
                                            class="mt-3 sm:mt-0 w-full sm:w-auto px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($errors->has('save'))
                    <div class="mt-4 text-red-600 text-sm">{{ $errors->first('save') }}</div>
                @endif

                @if (session()->has('success'))
                    <div class="mt-4 text-green-700">{{ session('success') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>
