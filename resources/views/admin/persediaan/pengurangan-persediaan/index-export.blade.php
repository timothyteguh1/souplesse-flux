<x-admin::layouts.export>
    <table class="bordered text-left">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Gudang</th>
                <th>Keterangan</th>
                <th class="text-end">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->kode }}</td>
                    <td>{{ _date_format_output($obj->tanggal) }}</td>
                    <td>{{ $obj->gudang->nama }}</td>
                    <td>{{ $obj->keterangan }}</td>
                    <td class="text-end">{{ _numberReport($obj->grandtotal) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
