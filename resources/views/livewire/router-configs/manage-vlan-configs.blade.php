<div>
    <h2 class="text-lg font-bold mb-4">VLAN Configurations</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">VLAN ID</th>
                <th class="px-4 py-2">Name</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vlans as $vlan)
                <tr>
                    <td class="border px-4 py-2">{{ $vlan->id }}</td>
                    <td class="border px-4 py-2">{{ $vlan->vlan_id }}</td>
                    <td class="border px-4 py-2">{{ $vlan->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
