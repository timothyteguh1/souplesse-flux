<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Penjualan" :file_type="$file_type">
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Customer : {{ $isSemuaCustomer ? 'Semua Customer' : $customers->pluck('nama')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <?php
        $objs = App\Models\Penjualan\FakturPenjualan::query()
            ->when($tanggal, function ($query) use ($tanggal) {
                return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
            })
            ->whereIn('customer_id', $customerIds)
            ->get();
        ?>

        <div class="col-12">
            <table class="bordered mb-20">
                <thead>
                    <tr class="text-center">
                        <th>Tanggal</th>
                        <th>No. Referensi</th>
                        <th>Produk</th>
                        <th>Satuan</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Harga</th>
                        <th class="text-end">Diskon</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($objs as $obj)
                        @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $item)
                            <tr>
                                <td>
                                    {{ _date_format_output($obj->tanggal) }}
                                </td>
                                <td>{{ $obj->kode }}</td>
                                <td>{{ $item->produk->nama }}</td>
                                <td>{{ $item->satuan->nama }}</td>
                                <td class="text-end">
                                    {{ _numberReport($item->jumlah, $file_type) }}
                                </td>
                                <td class="text-end">
                                    {{ _numberReport($item->harga, $file_type) }}
                                </td>
                                <td class="text-end">
                                    {{ _numberReport($item->diskon, $file_type) }}
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
                        <th class="text-center" colspan="7">Total</th>
                        <td class="text-end">
                            {{ _numberReport($objs->sum('grandtotal'), $file_type) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts.export>
