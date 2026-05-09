<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Kartu Stok" :file_type="$file_type">
        <tr>
            <td>Periode : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Gudang : {{ count($gudang_ids) == 0 ? 'Semua Gudang' : $gudangs?->pluck('nama')->implode(', ') }}</td>
        </tr>
        <tr>
            <td>Produk : {{ count($produk_ids) == 0 ? 'Semua Produk' : $produks?->pluck('nama')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <div class="col-12">
            @foreach ($gudangs as $gudang)
                <?php
                $mutasiStokGudangs = App\Models\System\MutasiStok::query()
                    ->when($tanggal, function ($query) use ($tanggal) {
                        return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                    })
                    ->where('gudang_id', $gudang->id)
                    ->get();
                ?>

                @if (count($mutasiStokGudangs))
                    <div class="fw-bold">Gudang {{ $gudang->nama }}</div>
                    @foreach ($produks as $produk)
                        <?php
                        $isSaldoAwal = true;

                        $mutasiJumlahMasuk = 0;
                        $mutasiJumlahKeluar = 0;

                        $mutasiStoks = App\Models\System\MutasiStok::query()
                            ->when($tanggal, function ($query) use ($tanggal) {
                                return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                            })
                            ->where('gudang_id', $gudang->id)
                            ->where('produk_id', $produk->id)
                            ->get();
                        ?>

                        @if (count($mutasiStoks))
                            <div class="mt-3">
                                <table>
                                    <tr>
                                        <td>{{ $produk->kode }} &mdash; {{ $produk->nama }}</td>
                                    </tr>
                                </table>
                                <table class="bordered mb-10">
                                    <thead>
                                        <tr class="text-center">
                                            <th>No</th>
                                            <th>No. Dokumen / Tanggal</th>
                                            <th>Qty Masuk</th>
                                            <th>Qty Keluar</th>
                                            <th>Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mutasiStoks as $mutasiStok)
                                            <?php
                                            $mutasiStok->loadMissing('reference.header');
                                            ?>

                                            @if ($isSaldoAwal)
                                                <?php
                                                $stokAwal = \App\Utilities\Functions\InventoryFunction::getStok(
                                                    produk_id: $mutasiStok->produk_id,
                                                    gudang_id: $mutasiStok->gudang_id,
                                                    tanggal: $tanggalAwal,
                                                    is_tanggal_inclusive: false,
                                                );
                                                ?>

                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>Saldo Awal</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td class="text-end">
                                                        {{ _numberReport($stokAwal, $file_type) }}
                                                    </td>
                                                </tr>
                                            @endif

                                            <?php
                                            $isSaldoAwal = false;
                                            if ($mutasiStok->jumlah > 0) {
                                                $mutasiJumlahMasuk += $mutasiStok->jumlah;
                                            } else {
                                                $mutasiJumlahKeluar += $mutasiStok->jumlah;
                                            }
                                            $stokAwal += $mutasiStok->jumlah;
                                            ?>

                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration + 1 }}
                                                </td>
                                                <td>
                                                    {{ $mutasiStok->header?->kode }}
                                                    <br />
                                                    {{ $mutasiStok->tanggal }}
                                                </td>
                                                <td class="text-end">
                                                    {{ $mutasiStok->jumlah > 0 ? _numberReport($mutasiStok->jumlah, $file_type) : '' }}
                                                </td>
                                                <td class="text-end">
                                                    {{ $mutasiStok->jumlah > 0 ? '' : _numberReport($mutasiStok->jumlah, $file_type) }}
                                                </td>
                                                <td class="text-end">{{ _numberReport($stokAwal, $file_type) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-center">Total Mutasi</th>
                                            <th class="text-end">
                                                {{ _numberReport($mutasiJumlahMasuk, $file_type) }}
                                            </th>
                                            <th class="text-end">
                                                {{ _numberReport($mutasiJumlahKeluar, $file_type) }}
                                            </th>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
</x-admin::layouts.export>
