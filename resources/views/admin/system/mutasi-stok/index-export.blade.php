<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Cabang</th>
                <th>Jenis Transaksi</th>
                <th>Gudang</th>
                <th>Produk</th>
                <th>Satuan Transaksi</th>
                <th>Reference</th>
                <th>ED</th>
                <th>No Batch</th>
                <th class="text-right">Jumlah Transaksi</th>
                <th class="text-right">Harga Transaksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>
                        {{ $obj->cabang->nama }}
                    </td>
                    <td>{{ $obj->jenis_transaksi }}</td>
                    <td>
                        {{ $obj->gudang?->nama }}
                    </td>
                    <td>
                        {{ $obj->produk?->nama }}
                    </td>
                    <td>
                        {{ $obj->satuanTransaksi?->nama }}
                    </td>
                    <td>
                        {{ $obj->header?->kode }}
                    </td>
                    <td>{{ $obj->expired_date }}</td>
                    <td>{{ $obj->no_batch }}</td>
                    <td class="text-end">{{ _numberReport($obj->jumlah_transaksi, $file_type) }}</td>
                    <td class="text-end">{{ _numberReport($obj->harga_transaksi, $file_type) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
