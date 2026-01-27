<div class="p-6">
    <h1 class="text-2xl font-bold mb-2">Session Details</h1>
    <div class="mb-4">
        <div><span class="font-semibold">MAC:</span> {{ $session->mac_address }}</div>
        <div><span class="font-semibold">IP:</span> {{ $session->ip_address }}</div>
        <div><span class="font-semibold">Username:</span> {{ $session->username }}</div>
        <div><span class="font-semibold">Router:</span> {{ $session->router->name ?? '-' }}</div>
        <div><span class="font-semibold">Started:</span> {{ $session->started_at }}</div>
        <div><span class="font-semibold">Last Seen:</span> {{ $session->last_seen_at }}</div>
        <div><span class="font-semibold">Status:</span> {{ $session->active ? 'Active' : 'Ended' }}</div>
    </div>
    <div class="mb-6">
        <nav class="flex gap-2 border-b border-gray-200">
            <button x-data x-on:click="$dispatch('tab', {tab: 'actions'})" class="px-4 py-2 -mb-px border-b-2 font-medium focus:outline-none border-blue-600 text-blue-600">Actions</button>
            <button x-data x-on:click="$dispatch('tab', {tab: 'billing'})" class="px-4 py-2 -mb-px border-b-2 font-medium focus:outline-none border-transparent text-gray-500 hover:text-blue-600">Billing</button>
        </nav>
    </div>
    <div x-data="{ tab: 'actions' }" @tab.window="tab = $event.detail.tab">
        <div x-show="tab === 'actions'">
            @livewire('sessions.session-enforcements', ['sessionId' => $session->id])
        </div>
        <div x-show="tab === 'billing'">
            @livewire('sessions.billing-sessions', ['sessionId' => $session->id])
        </div>
    </div>
</div>
