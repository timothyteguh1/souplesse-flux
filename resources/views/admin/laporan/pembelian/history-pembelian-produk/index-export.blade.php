<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan History Pembelian Produk" :file_type="$file_type">
        <tr>
            <td>Cabang : {{ $cabangs?->pluck('nama')->implode(', ') }}</td>
        </tr>
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Supplier : {{ $isSemuaSupplier ? 'Semua Supplier' : $suppliers?->pluck('nama')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <?php
        $objs = \App\Models\Pembelian\FakturPembelianDetail::query()
            ->with(['produk', 'satuan', 'fakturPembelian', 'fakturPembelian.supplier'])
            ->whereIn('produk_id', $produkIds)
            ->when($tanggal, function ($query) use ($tanggal) {
                return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRangeRelation($query, $tanggal, 'fakturPembelian', 'tanggal');
            })
            ->whereRelation('fakturPembelian', function ($query) use ($cabangIds, $supplierIds) {
                return $query->whereIn('cabang_id', $cabangIds)->whereIn('supplier_id', $supplierIds);
            })
            ->get();
        ?>

        <div class="col-12">
            <table class="bordered mb-20">
                <thead>
                    <tr class="text-center">
                        <th width="8%">Tanggal</th>
                        <th width="8%">No. Referensi</th>
                        <th width="8%">Supplier</th>
                        <th width="15%">Produk</th>
                        <th width="8%" class="text-right">Qty</th>
                        <th width="8%">Harga</th>
                        <th colspan="2">Diskon Item</th>
                        <th width="8%">Diskon Faktur</th>
                        <th width="8%">Beban Lain</th>
                        <th width="8%">DPP</th>
                        <th width="8%">PPN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($objs as $obj)
                        <tr>
                            <td>{{ _date_format_output($obj->header->tanggal) }}</td>
                            <td>
                                {{ $obj->header->kode }}
                            </td>
                            <td>
                                {{ $obj->header->supplier->nama }}
                            </td>
                            <td>
                                {{ $obj->produk->nama }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->jumlah) }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->harga_net_satuan) }}
                            </td>
                            <td width="5%" class="text-end">
                                {{ _number($obj->diskon_satuan_persen) }}%
                            </td>
                            <td width="8%" class="text-end">
                                {{ _number($obj->diskon_satuan_rupiah) }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->diskon_satuan_footer) }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->beban_satuan_footer) }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->dpp) }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->ppn) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end" colspan="10">TOTAL</th>
                        <td class="text-end">
                            {{ _number($objs->sum('dpp')) }}
                        </td>
                        <td class="text-end">
                            {{ _number($objs->sum('ppn')) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts.export>
