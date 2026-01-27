<div>
    <h2 class="text-lg font-bold mb-4">IP Address Configurations</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Interface Name</th>
                <th class="px-4 py-2">IP Address</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ipAddresses as $ip)
                <tr>
                    <td class="border px-4 py-2">{{ $ip->id }}</td>
                    <td class="border px-4 py-2">{{ $ip->interface_name }}</td>
                    <td class="border px-4 py-2">{{ $ip->ip_address }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
