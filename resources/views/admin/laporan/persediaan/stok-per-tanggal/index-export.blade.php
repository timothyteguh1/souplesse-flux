<x-admin::layouts.export>
    <x-admin::includes.reports.export-header title="Laporan Stok Per Tanggal" :file_type="$file_type">
        <tr>
            <td>Tanggal : {{ $tanggal }}</td>
        </tr>
        <tr>
            <td>Gudang : {{ count($gudang_ids) == 0 ? 'Semua Gudang' : $gudangs->pluck('nama')->implode(', ') }}</td>
        </tr>
    </x-admin::includes.reports.export-header>

    <div class="row mt-10">
        <div class="col-12">
            @foreach ($gudangs as $gudang)
                <?php
                $mutasiStokGudangs = App\Models\System\MutasiStok::query()
                    ->with(['produk'])
                    ->where('tanggal', '<=', $tanggalCarbon)
                    ->where('gudang_id', $gudang->id)
                    ->groupBy('produk_id')
                    ->selectRaw('produk_id, sum(jumlah) as jumlah')
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
                                <th class="text-end">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mutasiStokGudangs as $mutasiStokGudang)
                                <tr>
                                    <td>{{ $mutasiStokGudang->produk->kode }}</td>
                                    <td>{{ $mutasiStokGudang->produk->nama }}</td>
                                    <td class="text-end">
                                        {{ _numberReport($mutasiStokGudang->jumlah, $file_type) }}
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
