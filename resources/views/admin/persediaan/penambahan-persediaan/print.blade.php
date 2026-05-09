<x-admin::layouts.pdf :title="'Penyesuaian Tambah ' . $obj->kode">
    <div class="mb-10">
        @include('admin.components.includes.reports.perusahaan-logo')
    </div>

    <hr />

    <div class="mb-10">
        <table class="valign-top">
            <tr>
                <td class="font-20"><strong>PENYESUAIAN TAMBAH</strong></td>
            </tr>
            <tr>
                <td class="one-third">
                    <table class="table-spaced">
                        <tbody>
                            <tr>
                                <td width="15%">Kode</td>
                                <td width="2%">:</td>
                                <td width="83%">{{ $obj->kode }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal</td>
                                <td>:</td>
                                <td>{{ $obj->tanggal }}</td>
                            </tr>
                            <tr>
                                <td>Gudang</td>
                                <td>:</td>
                                <td>{{ $obj->gudang->nama }}</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:</td>
                                <td>{{ $obj->keterangan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="separator"></div>

    <div>
        <div><strong>Rincian Penyesuaian</strong></div>
        <table class="bordered">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="40%">Produk</th>
                    <th width="15%" class="text-right">Qty Tambah</th>
                    <th width="20%" class="text-right">Nilai</th>
                    <th width="20%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($obj->details()->with(['produk'])->get() as $item)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>
                            {{ $item->produk->nama }}
                        </td>
                        <td class="text-right">
                            {{ _number($item->jumlah) }}
                        </td>
                        <td class="text-right">
                            {{ _number($item->harga_satuan) }}
                        </td>
                        <td class="text-right">
                            {{ _number($item->subtotal) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th class="text-right" colspan="4">Total Nilai Persediaan yang Bertambah</th>
                    <th class="text-right">
                        {{ _number($obj->grandtotal) }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</x-admin::layouts.pdf>
