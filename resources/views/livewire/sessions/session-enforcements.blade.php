<div>
    <div class="flex gap-2 mb-4">
        <button wire:click="enforce('disconnect')" class="px-3 py-1 bg-red-600 text-white rounded">🔌 Disconnect</button>
        <button wire:click="enforce('limit_speed')" class="px-3 py-1 bg-yellow-500 text-white rounded">🐢 Limit Speed</button>
        <button wire:click="enforce('extend_time')" class="px-3 py-1 bg-blue-500 text-white rounded">⏱ Extend Time</button>
        <button wire:click="enforce('block')" class="px-3 py-1 bg-gray-700 text-white rounded">⛔ Block Device</button>
    </div>
    <table class="min-w-full text-xs">
        <thead>
            <tr>
                <th>Action</th>
                <th>Target</th>
                <th>Router</th>
                <th>Status</th>
                <th>Applied at</th>
                <th>Expires at</th>
                <th>Failure reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enforcements as $enf)
            <tr>
                <td>{{ $enf->action }}</td>
                <td>{{ $enf->mac_address ?? $enf->ip_address }}</td>
                <td>{{ $enf->router->name ?? '-' }}</td>
                <td>
                    @if($enf->status === 'pending')
                        <span class="animate-spin inline-block w-3 h-3 border-2 border-gray-400 border-t-transparent rounded-full"></span>
                    @endif
                    {{ ucfirst($enf->status) }}
                </td>
                <td>{{ $enf->applied_at }}</td>
                <td>{{ $enf->expires_at }}</td>
                <td class="text-red-600">{{ $enf->failure_reason }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
