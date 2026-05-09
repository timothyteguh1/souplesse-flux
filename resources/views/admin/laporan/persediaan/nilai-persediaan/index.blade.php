<div>
    @section('title', $menuTitle)

    <form wire:submit="prosesLihat">
        <x-admin::includes.pages.report-page-title />

        <div class="row">
            <div class="col-12">
                <x-admin::includes.alert-messages />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <x-admin::includes.pages.report-filter>
                        <div class="row g-3">
                            {{-- <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'cabang_ids'" :name="'cabang_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Cabang::user()"
                                    :placeholder="'- Semua Cabang -'" />
                            </div> --}}
                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.date :name="'tanggal'" placeholder="Masukkan Tanggal" />
                            </div>

                            <div class="col-8">
                                <x-admin::buttons.report-lihat />
                            </div>
                        </div>
                    </x-admin::includes.pages.report-filter>
                </div>

                @if ($is_lihat)
                    <x-admin::includes.pages.report-content>
                        <?php
                        $mutasiStoks = App\Models\System\MutasiStok::query()
                            ->with(['produk.kategoriProduk', 'satuan'])
                            ->join('produks', 'mutasi_stoks.produk_id', '=', 'produks.id')
                            ->whereIn('mutasi_stoks.cabang_id', $cabangIds)
                            ->where('tanggal', '<', $tanggalCarbon)
                            ->groupBy('produk_id', 'satuan_id')
                            ->orderBy('produks.nama')
                            ->select('produk_id', 'satuan_id', \Illuminate\Support\Facades\DB::raw('SUM(jumlah) as jumlah'), \Illuminate\Support\Facades\DB::raw('sum(jumlah*harga) / sum(jumlah) AS hpp'))
                            ->get();
                        $totalNilaiPersediaan = 0;
                        ?>

                        <div class="row">
                            <div class="col-12 text-center h3">NILAI PERSEDIAAN</div>
                            <div class="col-12 my-3">
                                <table>
                                    <tr>
                                        <th width="100px">Cabang</th>
                                        <th>: {{ $cabangs?->pluck('nama')->implode(', ') }}</th>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>: {{ $tanggal }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="20%">Kode</th>
                                            <th width="20%">Nama</th>
                                            <th width="15%">Kategori</th>
                                            <th width="15%">Satuan</th>
                                            <th width="10%" class="text-end">Qty</th>
                                            <th width="10%" class="text-end">Hpp Satuan</th>
                                            <th width="10%" class="text-end">Hpp Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mutasiStoks as $mutasiStok)
                                            <?php
                                            $totalHpp = $mutasiStok->jumlah * $mutasiStok->hpp;
                                            $totalNilaiPersediaan += $totalHpp;
                                            ?>

                                            <tr>
                                                <td>
                                                    <a href="{{ $mutasiStok->produk->getRouteShow() }}" target="_blank">
                                                        {{ $mutasiStok->produk->kode }}
                                                    </a>
                                                </td>
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
                                                    {{ _number($mutasiStok->jumlah) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($mutasiStok->hpp) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($totalHpp) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-end">Total Nilai Persediaan</th>
                                            <th class="text-end">{{ _number($totalNilaiPersediaan) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </x-admin::includes.pages.report-content>
                @endif
            </div>
        </div>
    </form>
</div>
