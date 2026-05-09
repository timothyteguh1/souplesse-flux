<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Cabang</th>
                <th>Jenis Transaksi</th>
                <th>Jenis</th>
                <th>Vendor</th>
                <th>Reference</th>
                <th>Keterangan</th>
                <th class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->cabang->nama }}</td>
                    <td>{{ $obj->jenis_transaksi }}</td>
                    <td>{{ $obj->jenis }}</td>
                    <td>
                        {{ $obj->vendor?->nama }}
                    </td>
                    <td>
                        {{ $obj->header?->kode }}
                    </td>
                    <td>{{ $obj->keterangan }}</td>
                    <td class="text-end">{{ _numberReport($obj->jumlah, $file_type) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
