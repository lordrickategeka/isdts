<div>
    <h2 class="text-lg font-bold mb-4">Bridge Configurations</h2>
    <table class="min-w-full bg-white">
        <thead>
            <tr>
                <th class="px-4 py-2">ID</th>
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Description</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bridges as $bridge)
                <tr>
                    <td class="border px-4 py-2">{{ $bridge->id }}</td>
                    <td class="border px-4 py-2">{{ $bridge->name }}</td>
                    <td class="border px-4 py-2">{{ $bridge->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
