<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Faktur Pembelian" :file_type="$file_type">
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Supplier : {{ $isSemuaSupplier ? 'Semua Supplier' : $suppliers?->pluck('nama')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <?php
        $objs = App\Models\Pembelian\FakturPembelian::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->whereIn('supplier_id', $supplierIds)
            ->get();
        $grandtotal = 0;
        ?>

        <div class="col-12">
            <table class="bordered mb-20">
                <thead>
                    <tr class="text-center">
                        <th>Tanggal</th>
                        <th>No. Referensi</th>
                        <th>Produk</th>
                        <th class="text-end">Qty</th>
                        <th>Satuan</th>
                        <th>Harga Beli Net</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($objs as $obj)
                        @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $item)
                            <?php
                            $grandtotal += $item->subtotal;
                            ?>

                            <tr>
                                <td>
                                    {{ _date_format_output($obj->tanggal) }}
                                </td>
                                <td>{{ $obj->kode }}</td>
                                <td>{{ $item->produk->nama }}</td>
                                <td class="text-end">
                                    {{ _numberReport($item->jumlah, $file_type) }}
                                </td>
                                <td>{{ $item->satuan->nama }}</td>
                                <td class="text-end">
                                    {{ _numberReport($item->harga_net, $file_type) }}
                                </td>
                                <td class="text-end">
                                    {{ _numberReport($item->subtotal, $file_type) }}
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-center" colspan="6">Total</th>
                        <td class="text-end">
                            {{ _numberReport($grandtotal, $file_type) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts.export>
