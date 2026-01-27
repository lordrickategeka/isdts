<div>
    <table class="min-w-full text-xs">
        <thead>
            <tr>
                <th>Customer / Voucher</th>
                <th>Billing Type</th>
                <th>Start</th>
                <th>End</th>
                <th>Data Used</th>
                <th>Amount Charged</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($billingSessions as $bill)
            <tr>
                <td>{{ $bill->billing_identifier }}</td>
                <td>{{ ucfirst($bill->billing_type) }}</td>
                <td>{{ $bill->billable_start_at }}</td>
                <td>{{ $bill->billable_end_at }}</td>
                <td>{{ number_format($bill->bytes_in + $bill->bytes_out) }} bytes</td>
                <td>{{ $bill->amount_charged }} {{ $bill->currency }}</td>
                <td>{{ ucfirst($bill->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
