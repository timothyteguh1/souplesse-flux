<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Mutasi Nilai Persediaan" :file_type="$file_type">
        <tr>
            <td>Periode : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Produk : {{ $produk->nama }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <?php
        $saldoAwal = App\Models\System\MutasiStok::query()
            ->select(\Illuminate\Support\Facades\DB::raw('SUM(jumlah) as jumlah'), DB::raw('SUM(harga*jumlah) as harga'))
            ->where('tanggal', '<', $tanggalAwal)
            ->where('produk_id', $produk_id)
            ->first();
        $jumlahAwal = $saldoAwal->jumlah ? $saldoAwal->jumlah : 0;
        $nilaiSatuanAwal = $saldoAwal->jumlah ? $saldoAwal->harga / $saldoAwal->jumlah : 0;
        $totalPersediaanAwal = $jumlahAwal * $nilaiSatuanAwal;

        $mutasiStoks = App\Models\System\MutasiStok::query()
            ->with(['produk', 'reference.header'])
            ->where('tanggal', '>=', $tanggalAwal)
            ->where('tanggal', '<=', $tanggalAkhir)
            ->where('produk_id', $produk_id)
            ->get();
        $totalNilaiPersediaan = 0;
        ?>

        <div class="col-12">
            <table class="bordered mb-20">
                <thead>
                    <tr class="text-center">
                        <th rowspan="2">Tanggal</th>
                        <th rowspan="2">Transaksi</th>
                        <th rowspan="2">Referensi</th>
                        <th colspan="2">Awal</th>
                        <th colspan="2">Mutasi</th>
                        <th colspan="3">Akhir</th>
                    </tr>
                    <tr class="text-center">
                        <th>Jumlah</th>
                        <th>Nilai Satuan</th>
                        <th>Jumlah</th>
                        <th>Nilai Satuan</th>
                        <th>Jumlah</th>
                        <th>HPP</th>
                        <th>Total Persediaan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            {{ _date_format_output($tanggalAwal) }}
                        </td>
                        <td>SALDO AWAL</td>
                        <td></td>
                        <td class="text-end">
                            {{ _numberReport($jumlahAwal, $file_type) }}
                        </td>
                        <td class="text-end">
                            {{ _numberReport($nilaiSatuanAwal, $file_type) }}
                        </td>
                        <td></td>
                        <td></td>
                        <td class="text-end">
                            {{ _numberReport($jumlahAwal, $file_type) }}
                        </td>
                        <td class="text-end">
                            {{ _numberReport($nilaiSatuanAwal, $file_type) }}
                        </td>
                        <td class="text-end">
                            {{ _numberReport($totalPersediaanAwal, $file_type) }}
                        </td>
                    </tr>
                    @foreach ($mutasiStoks as $mutasiStok)
                        <tr>
                            <td>
                                {{ _date_format_output($mutasiStok->tanggal) }}
                            </td>
                            <td>
                                {{ $mutasiStok->keterangan }}
                            </td>
                            <td>{{ $mutasiStok->header->kode }}</td>
                            <td class="text-end">
                                {{ _numberReport($jumlahAwal, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($nilaiSatuanAwal, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($mutasiStok->jumlah, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($mutasiStok->harga, $file_type) }}
                            </td>

                            <?php
                            $jumlahAwal += $mutasiStok->jumlah;
                            $nilaiSatuanAwal = ($totalPersediaanAwal + $mutasiStok->jumlah * $mutasiStok->harga) / $jumlahAwal;
                            $totalPersediaanAwal = $jumlahAwal * $nilaiSatuanAwal;
                            ?>

                            <td class="text-end">
                                {{ _numberReport($jumlahAwal, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($nilaiSatuanAwal, $file_type) }}
                            </td>
                            <td class="text-end">
                                {{ _numberReport($totalPersediaanAwal, $file_type) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-admin::layouts.export>
