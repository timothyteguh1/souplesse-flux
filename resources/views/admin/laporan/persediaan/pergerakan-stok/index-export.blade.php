<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Pergerakan Stok" :file_type="$file_type">
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Gudang : {{ count($gudang_ids) == 0 ? 'Semua Gudang' : $gudangs->pluck('nama')->implode(', ') }}</td>
        </tr>
        <tr>
            <td>Produk : {{ count($produk_ids) == 0 ? 'Semua Produk' : $produks->pluck('nama')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <div class="col-12">
            @foreach ($gudangs as $gudang)
                <?php
                $mutasiStokGudangs = App\Models\System\MutasiStok::query()
                    ->with(['produk'])
                    ->whereIn('produk_id', $produkIds)
                    ->where('gudang_id', $gudang->id)
                    ->groupBy('produk_id', 'gudang_id')
                    ->selectRaw('produk_id, gudang_id')
                    ->get();
                ?>

                @if (count($mutasiStokGudangs))
                    <table>
                        <tr>
                            <th class="text-left">Gudang {{ $gudang->nama }}</th>
                        </tr>
                    </table>

                    <table class="bordered mb-20">
                        <thead>
                            <tr class="text-center">
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th class="text-end">Awal</th>
                                <th class="text-end">Mutasi</th>
                                <th class="text-end">Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mutasiStokGudangs as $mutasiStokGudang)
                                <tr>
                                    <td>{{ $mutasiStokGudang->produk->kode }}</td>
                                    <td>
                                        {{ $mutasiStokGudang->produk->nama }}
                                    </td>
                                    <td class="text-end">
                                        <?php
                                        $stokAwal = \App\Utilities\Functions\InventoryFunction::getStok(
                                            produk_id: $mutasiStokGudang->produk_id,
                                            gudang_id: $mutasiStokGudang->gudang_id,
                                            tanggal: $tanggalAwal,
                                            is_tanggal_inclusive: false,
                                        );
                                        ?>

                                        {{ _numberReport($stokAwal, $file_type) }}
                                    </td>
                                    <td class="text-end">
                                        <?php
                                        $stokMutasi = \App\Utilities\Functions\InventoryFunction::getStokMutasi(
                                            produk_id: $mutasiStokGudang->produk_id,
                                            gudang_id: $mutasiStokGudang->gudang_id,
                                            tanggal_awal: $tanggalAwal,
                                            tanggal_akhir: $tanggalAkhir,
                                        );
                                        ?>

                                        {{ _numberReport($stokMutasi, $file_type) }}
                                    </td>
                                    <td class="text-end">
                                        <?php
                                        $stokAkhir = \App\Utilities\Functions\InventoryFunction::getStok(
                                            produk_id: $mutasiStokGudang->produk_id,
                                            gudang_id: $mutasiStokGudang->gudang_id,
                                            tanggal: $tanggalAkhir,
                                        );
                                        ?>

                                        {{ _numberReport($stokAkhir, $file_type) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
        </div>
    </div>
</x-admin::layouts.export>
