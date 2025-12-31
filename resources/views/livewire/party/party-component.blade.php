<div class="p-4 bg-base-100">
    <div class="flex items-center justify-between mb-4">
        <div>
            <h2 class="text-2xl font-bold text-black">Parties</h2>
            <p class="text-sm text-gray-700">All parties in the system — people and companies</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('parties.create') }}" class="btn btn-primary">+ New Party</a>
            <button class="btn btn-outline btn-primary" title="Import">Import</button>
            <button class="btn btn-outline btn-primary" title="Export">Export</button>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-3 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg">{{ session('error') }}</div>
    @endif

    <!-- Search and Filter Bar (Products-style) -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-4">
        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="flex-1 w-full">
                <div class="relative">
                    <input type="text" wire:model.debounce.300ms="query"
                           class="w-full px-4 py-1.5 pl-9 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Search name, phone, email...">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400 absolute left-2.5 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <select wire:model="filterType" class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Any Type</option>
                    <option value="person">Person</option>
                    <option value="company">Company</option>
                </select>
                <select wire:model="filterCategory" class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Any Category</option>
                    @foreach($categories as $c)
                        <option value="{{ is_array($c) ? $c['name'] : $c->name }}">{{ is_array($c) ? $c['name'] : $c->name }}</option>
                    @endforeach
                </select>
                <label class="text-xs text-gray-600">Show:</label>
                <select wire:model="perPage" class="px-2 py-1.5 text-xs border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="6">6</option>
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <div class="flex h-full">
        <!-- LEFT: List/Table -->
        <section class="w-1/2 border-r bg-base-100 overflow-auto">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="overflow-x-auto">
    <table class="w-full text-xs">
        <thead class="bg-gray-50 border-b border-gray-200">
            <tr>
                <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Select</th>
                <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Party</th>
                <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th class="px-3 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
            </tr>
        </thead>
        <tbody class="bg-white">
            @forelse(($parties ?? $this->filtered) as $party)
                <tr class="{{ $loop->index % 2 === 0 ? 'bg-white' : 'bg-gray-200' }} hover:bg-blue-200 transition-colors duration-150 cursor-pointer" wire:click="select({{ is_array($party) ? $party['id'] : $party->id }})">
                    <td class="px-3 py-1.5">
                        <input type="checkbox" />
                    </td>
                    <td class="px-3 py-1.5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-black font-semibold text-[11px]">
                                @php
                                    $name = is_array($party)
                                        ? ($party['display'] ?? ($party['name'] ?? (($party['first_name'] ?? '') . ' ' . ($party['last_name'] ?? ''))))
                                        : ($party->party_type === 'company' ? ($party->name ?? '') : trim(($party->first_name ?? '') . ' ' . ($party->last_name ?? '')));
                                    $initials = '';
                                    foreach (preg_split('/\s+/', trim($name)) as $p) { $initials .= strtoupper(substr($p,0,1)); }
                                    $initials = substr($initials,0,2);
                                @endphp
                                {{ $initials }}
                            </div>
                            <div>
                                <div class="text-black font-semibold text-sm">
                                    {{ is_array($party)
                                        ? ($party['display'] ?? '')
                                        : ($party->party_type === 'company' ? $party->name : trim(($party->first_name ?? '') . ' ' . ($party->last_name ?? ''))) }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ is_array($party) ? ($party['meta'] ?? '') : ($party->category?->name ?? '—') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-1.5 text-gray-600">{{ is_array($party) ? ($party['category'] ?? '—') : ($party->category?->name ?? '—') }}</td>
                    <td class="px-3 py-1.5 text-gray-600">
                        @if(is_array($party))
                            {{ $party['contact'] ?? '—' }}
                        @else
                            <div class="leading-tight">
                                <div class="text-xs">{{ $party->email ?: '—' }}</div>
                                <div class="text-xs">{{ $party->phone ?: '—' }}</div>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-3 py-3 text-center text-gray-500">No parties found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
                </div>
                <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                    @if(isset($parties) && method_exists($parties, 'links'))
                        {{ $parties->links() }}
                    @endif
                </div>
            </div>
        </section>

        <!-- RIGHT: Profile -->
        <aside class="w-1/2 bg-base-100 p-4 overflow-auto">
            @if($selected)
                <div class="h-full flex flex-col">
                    <div class="flex items-start gap-4 border-b pb-4">
                        <div class="w-20 h-20 card bg-base-100 shadow-md rounded-xl border border-gray-200 flex items-center justify-center text-2xl font-bold text-black">
                            @php
                                $sname = is_array($selected)
                                    ? ($selected['display'] ?? '')
                                    : ($selected->party_type === 'company' ? ($selected->name ?? '') : trim(($selected->first_name ?? '') . ' ' . ($selected->last_name ?? '')));
                                $sinit = '';
                                foreach (preg_split('/\s+/', trim($sname)) as $p) { $sinit .= strtoupper(substr($p,0,1)); }
                                $sinit = substr($sinit,0,2);
                            @endphp
                            {{ $sinit }}
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-black">
                                        {{ is_array($selected) ? ($selected['display'] ?? '') : ($selected->party_type === 'company' ? $selected->name : trim(($selected->first_name ?? '') . ' ' . ($selected->last_name ?? ''))) }}
                                    </h3>
                                    <div class="text-sm text-gray-700">{{ is_array($selected) ? ($selected['meta'] ?? '') : ($selected->category?->name ?? '—') }}</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button class="btn btn-outline btn-primary btn-sm">Edit</button>
                                    <button class="btn btn-error btn-sm" wire:click="confirmDeleteSelected">Delete</button>
                                    <button class="btn btn-ghost btn-square" title="More">
                                        <svg class="w-5 h-5 text-black" viewBox="0 0 24 24" fill="none" stroke="currentColor"><circle cx="5" cy="12" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($confirmingDelete)
                        <div class="mt-3 bg-red-50 border border-red-200 text-red-700 rounded-lg p-3 flex items-center justify-between">
                            <span class="text-sm">Delete this party? This cannot be undone.</span>
                            <div class="flex gap-2">
                                <button class="btn btn-error btn-sm" wire:click="deleteSelected">Delete</button>
                                <button class="btn btn-ghost btn-sm" wire:click="cancelDelete">Cancel</button>
                            </div>
                        </div>
                    @endif
                    <!-- Tabs -->
                    <div class="mt-4">
                        <div class="flex gap-1 border-b">
                            @php $tabs = ['Overview','Addresses','Contacts','Custom Fields','Related Parties','Tags']; @endphp
                            @foreach($tabs as $i => $tab)
                                <button class="px-2 py-1 -mb-px text-sm text-black rounded {{ $activeTab === $i ? 'border-b-2 border-primary' : '' }}" wire:click="setTab({{ $i }})">{{ $tab }}</button>
                            @endforeach
                        </div>

                        <div class="mt-4">
                            <!-- Overview -->
                            @if($activeTab === 0)
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="text-sm font-bold text-black">Basic</h4>
                                        <p class="mt-2 text-sm">{{ is_array($selected) ? ($selected['meta'] ?? '') : ($selected->party_type) }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-black">Contact</h4>
                                        @if(is_array($selected))
                                            <p class="mt-2 text-sm">{{ $selected['contact'] ?? '—' }}</p>
                                        @else
                                            <div class="mt-2 text-sm leading-tight">
                                                <div>{{ $selected->email ?: '—' }}</div>
                                                <div>{{ $selected->phone ?: '—' }}</div>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-black">Category</h4>
                                        <p class="mt-2 text-sm">{{ is_array($selected) ? ($selected['category'] ?? '') : ($selected->category?->name ?? '—') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-sm font-bold text-black">Notes</h4>
                                        <p class="mt-2 text-sm text-gray-700">{{ is_array($selected) ? '—' : ($selected->notes ?? '—') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Addresses -->
                            @if($activeTab === 1)
                                <div class="space-y-2">
                                    <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200 p-3">
                                        <div class="text-sm text-gray-500">No addresses</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Contacts -->
                            @if($activeTab === 2)
                                <div class="space-y-2">
                                    @if(!is_array($selected) && $selected->contacts && $selected->contacts->count())
                                        @foreach($selected->contacts as $contact)
                                            <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200 p-3">
                                                <div class="text-sm">{{ $contact->type }}: {{ $contact->value }}</div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200 p-3">
                                            <div class="text-sm text-gray-500">No contacts</div>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Custom Fields -->
                            @if($activeTab === 3)
                                <div class="space-y-3">
                                    <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200 p-3">
                                        <div class="text-sm text-gray-500">No custom fields</div>
                                    </div>
                                </div>
                            @endif

                            <!-- Related Parties -->
                            @if($activeTab === 4)
                                <div class="card bg-base-100 shadow-md rounded-lg border border-gray-200 p-3">No related parties.</div>
                            @endif

                            <!-- Tags -->
                            @if($activeTab === 5)
                                <div class="flex gap-2 items-center">
                                    @foreach($tags as $t)
                                        <button class="btn btn-sm" wire:click="toggleTagOnSelected('{{ $t['name'] }}')">{{ $t['name'] }}</button>
                                    @endforeach
                                </div>

                                <div class="mt-3">
                                    <h4 class="text-sm font-bold text-black">Applied Tags</h4>
                                    <div class="mt-2 flex gap-2">
                                        @if(is_array($selected))
                                            @forelse(($selected['tags'] ?? []) as $t)
                                                <span class="chip bg-primary/10 text-black">{{ $t }}</span>
                                            @empty
                                                <span class="text-xs text-gray-500">No tags</span>
                                            @endforelse
                                        @else
                                            @forelse(($selected->tags ?? collect()) as $t)
                                                <span class="chip bg-primary/10 text-black">{{ $t->name }}</span>
                                            @empty
                                                <span class="text-xs text-gray-500">No tags</span>
                                            @endforelse
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center h-full text-gray-400">
                    <div class="text-center">
                        <div class="text-3xl">👋</div>
                        <div class="mt-2">Select a party to view details</div>
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
