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
                                <x-admin::input.tags
                                    :id="'cabang_ids'"
                                    :name="'cabang_ids'"
                                    :options="\App\Utilities\SelectHelpers\Master\SH_Cabang::user()"
                                    :placeholder="'- Semua Cabang -'"
                                />
                            </div> --}}
                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.date-range :name="'tanggal'" placeholder="Masukkan Tanggal" />
                            </div>

                            <div class="col-xxl-8 col-sm-6">
                                <x-admin::input.select2id :id="'produk_id'" :name="'produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_Produk::activeWithStok()"
                                    :placeholder="'- Pilih Produk -'" />
                            </div>

                            <div class="col-xxl-12 col-sm-12">
                                <x-admin::buttons.report-lihat />
                            </div>
                        </div>
                    </x-admin::includes.pages.report-filter>
                </div>

                @if ($is_lihat)
                    <x-admin::includes.pages.report-content>
                        <?php
                        $saldoAwal = App\Models\System\MutasiStok::query()->select(DB::raw('SUM(jumlah) as jumlah'), DB::raw('SUM(harga*jumlah) as harga'))->whereIn('cabang_id', $cabangIds)->where('tanggal', '<', $tanggalAwal)->where('produk_id', $produk_id)->first();
                        $jumlahAwal = $saldoAwal->jumlah ? $saldoAwal->jumlah : 0;
                        $nilaiSatuanAwal = $saldoAwal->jumlah ? $saldoAwal->harga / $saldoAwal->jumlah : 0;
                        $totalPersediaanAwal = $jumlahAwal * $nilaiSatuanAwal;
                        
                        $mutasiStoks = App\Models\System\MutasiStok::query()
                            ->with(['produk', 'header'])
                            ->whereIn('cabang_id', $cabangIds)
                            ->where('tanggal', '>=', $tanggalAwal)
                            ->where('tanggal', '<=', $tanggalAkhir)
                            ->where('produk_id', $produk_id)
                            ->get();
                        $totalNilaiPersediaan = 0;
                        ?>

                        <div class="row">
                            <div class="col-12 text-center h3">MUTASI NILAI PERSEDIAAN</div>
                            <div class="col-12 my-3">
                                <table>
                                    <tr>
                                        <th width="100px">Cabang</th>
                                        <th>: {{ $cabangs?->pluck('nama')->implode(', ') }}</th>
                                    </tr>
                                    <tr>
                                        <th>Periode</th>
                                        <th>: {{ $tanggal }}</th>
                                    </tr>
                                    <tr>
                                        <th>Produk</th>
                                        <th>: {{ $produk->nama }}</th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="10%" rowspan="2">Tanggal</th>
                                            <th width="15%" rowspan="2">Transaksi</th>
                                            <th width="15%" rowspan="2">Referensi</th>
                                            <th width="20%" colspan="2">Awal</th>
                                            <th width="20%" colspan="2">Mutasi</th>
                                            <th width="20%" colspan="3">Akhir</th>
                                        </tr>
                                        <tr class="text-center">
                                            <th width="8%">Jumlah</th>
                                            <th width="8%">Nilai Satuan</th>
                                            <th width="8%">Jumlah</th>
                                            <th width="8%">Nilai Satuan</th>
                                            <th width="10%">Jumlah</th>
                                            <th width="10%">HPP</th>
                                            <th width="10%">Total Persediaan</th>
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
                                                {{ _number($jumlahAwal) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($nilaiSatuanAwal) }}
                                            </td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-end">
                                                {{ _number($jumlahAwal) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($nilaiSatuanAwal) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($totalPersediaanAwal) }}
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
                                                <td>
                                                    @if ($mutasiStok->jenis_transaksi === 'Stok Awal')
                                                        <a href="{{ route('admin.system.setting.stok-awal') }}"
                                                            target="_blank">
                                                            Stok Awal
                                                        </a>
                                                    @else
                                                        <a href="{{ $mutasiStok?->header?->getRouteShow() }}"
                                                            target="_blank">
                                                            {{ $mutasiStok?->header?->kode }}
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($jumlahAwal) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($nilaiSatuanAwal) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($mutasiStok->jumlah) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($mutasiStok->harga) }}
                                                </td>

                                                <?php
                                                $jumlahAwal += $mutasiStok->jumlah;
                                                $nilaiSatuanAwal = ($totalPersediaanAwal + $mutasiStok->jumlah * $mutasiStok->harga) / $jumlahAwal;
                                                $totalPersediaanAwal = $jumlahAwal * $nilaiSatuanAwal;
                                                ?>

                                                <td class="text-end">
                                                    {{ _number($jumlahAwal) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($nilaiSatuanAwal) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($totalPersediaanAwal) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </x-admin::includes.pages.report-content>
                @endif
            </div>
        </div>
    </form>
</div>
