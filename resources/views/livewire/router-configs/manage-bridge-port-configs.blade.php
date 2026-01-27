<div>
    <h2 class="text-lg font-bold mb-4">Bridge Port Configurations</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Bridge ID</th>
                <th class="px-4 py-2">Port Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bridgePorts as $port)
                <tr>
                    <td class="border px-4 py-2">{{ $port->id }}</td>
                    <td class="border px-4 py-2">{{ $port->router_bridge_id }}</td>
                    <td class="border px-4 py-2">{{ $port->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
