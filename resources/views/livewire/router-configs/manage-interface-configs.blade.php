<div>
    <h2 class="text-lg font-bold mb-4">Interface Configurations</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Router ID</th>
                <th class="px-4 py-2">Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($interfaces as $iface)
                <tr>
                    <td class="border px-4 py-2">{{ $iface->id }}</td>
                    <td class="border px-4 py-2">{{ $iface->router_id }}</td>
                    <td class="border px-4 py-2">{{ $iface->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
