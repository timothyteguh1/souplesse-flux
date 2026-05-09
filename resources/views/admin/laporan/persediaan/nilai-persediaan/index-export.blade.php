<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Nilai Persediaan" :file_type="$file_type">
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <?php
        $mutasiStoks = App\Models\System\MutasiStok::query()
            ->with(['produk.kategoriProduk', 'satuan'])
            ->join('produks', 'mutasi_stoks.produk_id', '=', 'produks.id')
            ->where('tanggal', '<', $tanggalCarbon)
            ->groupBy('produk_id', 'satuan_id')
            ->orderBy('produks.nama')
            ->select(
                'produk_id',
                'satuan_id',
                \Illuminate\Support\Facades\DB::raw('SUM(jumlah) as jumlah'),
                \Illuminate\Support\Facades\DB::raw('sum(jumlah*harga) / sum(jumlah) AS hpp'),
            )
            ->get();
        $totalNilaiPersediaan = 0;
        ?>

        <div class="col-12">
            <table class="bordered mb-20">
                <thead>
                    <tr class="text-center">
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Hpp Satuan</th>
                        <th class="text-end">Hpp Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mutasiStoks as $mutasiStok)
                        <?php
                        $totalHpp = $mutasiStok->jumlah * $mutasiStok->hpp;
                        $totalNilaiPersediaan += $totalHpp;
                        ?>

                        <tr>
                            <td>{{ $mutasiStok->produk->kode }}</td>
                            <td>
                                {{ $mutasiStok->produk->nama }}
                            </td>
                            <td>
                                {{ $mutasiStok->produk->kategoriProduk->nama }}
                            </td>
                            <td>
                                {{ $mutasiStok->satuan->nama }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($mutasiStok->jumlah, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($mutasiStok->hpp, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($totalHpp, $file_type) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="6" class="text-end">Total Nilai Persediaan</th>
                        <th class="text-end">{{ _numberReport($totalNilaiPersediaan, $file_type) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</x-admin::layouts.export>
