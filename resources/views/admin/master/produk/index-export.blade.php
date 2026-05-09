<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Supplier</th>
                <th>Satuan Dasar</th>
                <th>Kategori</th>
                <th>Brand</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->kode }}</td>
                    <td>{{ $obj->nama }}</td>
                    <td>
                        @if ($obj->supplier)
                            {{ $obj->supplier->nama }}
                        @endif
                    </td>
                    <td>
                        @if ($obj->produkSatuan)
                            {{ $obj->produkSatuan->satuan->nama }}
                        @endif
                    </td>
                    <td>
                        @if ($obj->kategoriProduk)
                            {{ $obj->kategoriProduk->nama }}
                        @endif
                    </td>
                    <td>
                        @if ($obj->brandProduk)
                            {{ $obj->brandProduk->nama }}
                        @endif
                    </td>
                    <td>{{ $obj->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
