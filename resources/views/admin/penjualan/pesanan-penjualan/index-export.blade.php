<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Customer</th>
                <th class="text-end">Grand Total</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->kode }}</td>
                    <td>{{ _date_format_output($obj->tanggal) }}</td>
                    <td>{{ $obj->customer?->nama }}</td>
                    <td class="text-end">{{ _numberReport($obj->grandtotal) }}</td>
                    <td>{{ $obj->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
