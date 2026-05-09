<x-admin::layouts.export>
    <table class="bordered text-left">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Kategori</th>
                <th class="text-end">Jml. Satuan Dasar</th>
                <th class="text-end">Jml. Multi Satuan</th>
                <th>Gudang</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data->loadMissing(['gudang', 'header', 'produk.kategoriProduk', 'satuan']) as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->produk->kode }}</td>
                    <td>{{ $obj->produk->nama }}</td>
                    <td>{{ optional($obj->produk->kategoriProduk)->nama }}</td>
                    <td class="text-end">{{ _numberReport($obj->total) }} {{ $obj->satuan->nama }}</td>
                    <td class="text-end">
                        <x-admin::utils.saldo-stok-multi-satuan
                            :produk_id="$obj->produk_id"
                            :total="$obj->total"
                            :as_string="true"
                        />
                    </td>
                    <td>{{ $obj->gudang->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
