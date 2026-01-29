@props(['conflicts', 'importConflictsPage' => 1, 'importConflictsPerPage' => 10])
<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
    <div class="font-bold text-yellow-800 mb-2">Conflicts detected during import</div>
    <div class="text-sm text-yellow-700 mb-2">Review the differences and choose whether to update or skip each record.</div>
    <form method="POST" wire:submit.prevent="resolveConflicts">
        @csrf
        @php
            // Group conflicts by username (or customer_name if username missing)
            $grouped = collect($conflicts)->groupBy(function($c) {
                $username = $c['import']['username'] ?? null;
                return $username ?: ($c['customer_name'] ?? 'unknown');
            });
            $page = $importConflictsPage ?? 1;
            $perPage = $importConflictsPerPage ?? 10;
            $total = $grouped->count();
            $groupedPage = $grouped->slice(($page-1)*$perPage, $perPage);
            $lastPage = (int) ceil($total / $perPage);
        @endphp
        <div class="flex justify-between items-center mb-2">
            <div class="text-xs text-gray-700">Page {{ $page }} of {{ $lastPage }} ({{ $total }} unique customers with conflicts)</div>
            <div class="flex gap-1">
                <button type="button" wire:click="setImportConflictsPage(1)" @if($page==1) disabled @endif class="px-2 py-1 text-xs border rounded bg-gray-50 disabled:opacity-50">First</button>
                <button type="button" wire:click="setImportConflictsPage({{ max(1, $page-1) }})" @if($page==1) disabled @endif class="px-2 py-1 text-xs border rounded bg-gray-50 disabled:opacity-50">Prev</button>
                <button type="button" wire:click="setImportConflictsPage({{ min($lastPage, $page+1) }})" @if($page==$lastPage) disabled @endif class="px-2 py-1 text-xs border rounded bg-gray-50 disabled:opacity-50">Next</button>
                <button type="button" wire:click="setImportConflictsPage({{ $lastPage }})" @if($page==$lastPage) disabled @endif class="px-2 py-1 text-xs border rounded bg-gray-50 disabled:opacity-50">Last</button>
            </div>
        </div>
        <table class="min-w-full text-xs border">
            <thead>
                <tr>
                    <th class="border px-2 py-1">Row(s)</th>
                    <th class="border px-2 py-1">Customer Name</th>
                    <th class="border px-2 py-1">Username</th>
                    <th class="border px-2 py-1">Conflicting Fields</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedPage as $username => $conflictGroup)
                    @php
                        $first = $conflictGroup->first();
                        $rows = $conflictGroup->pluck('row')->implode(', ');
                        $customerName = $first['customer_name'] ?? '';
                    @endphp
                    <tr>
                        <td class="border px-2 py-1 align-top">{{ $rows }}</td>
                        <td class="border px-2 py-1 align-top">{{ $customerName }}</td>
                        <td class="border px-2 py-1 align-top">{{ $username }}</td>
                        <td class="border px-2 py-1">
                            <table class="w-full text-xs">
                                <thead>
                                    <tr>
                                        <th class="border px-1 py-0.5">Field</th>
                                        <th class="border px-1 py-0.5">Existing</th>
                                        <th class="border px-1 py-0.5">Import</th>
                                        <th class="border px-1 py-0.5">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($conflictGroup as $conflict)
                                        @foreach ($conflict['diffs'] as $field => $diff)
                                            <tr>
                                                <td class="border px-1 py-0.5">{{ $field }}</td>
                                                <td class="border px-1 py-0.5">{{ $diff['existing'] }}</td>
                                                <td class="border px-1 py-0.5">{{ $diff['import'] }}</td>
                                                <td class="border px-1 py-0.5">
                                                    <select name="actions[{{ $conflict['row'] }}][{{ $field }}]" class="border rounded px-1 py-0.5">
                                                        <option value="skip">Skip</option>
                                                        <option value="update">Update</option>
                                                    </select>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Apply Choices</button>
        </div>
    </form>
</div>
