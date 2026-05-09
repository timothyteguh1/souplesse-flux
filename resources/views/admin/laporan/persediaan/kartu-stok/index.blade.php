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
                            {{-- <div class="col-xxl-3 col-sm-6">
                                <x-admin::input.tags :id="'cabang_ids'" :name="'cabang_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Cabang::user()"
                                    :placeholder="'- Semua Cabang -'" />
                            </div> --}}

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.date-range :name="'tanggal'" placeholder="Masukkan Tanggal" />
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'gudang_ids'" :name="'gudang_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Gudang::user()"
                                    :placeholder="'- Semua Gudang -'" />
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'produk_ids'" :name="'produk_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Produk::activeWithStok()"
                                    :placeholder="'- Semua Produk -'" />
                            </div>

                            <div class="col-12">
                                <x-admin::buttons.report-lihat />
                            </div>
                        </div>
                    </x-admin::includes.pages.report-filter>
                </div>

                @if ($is_lihat)
                    <x-admin::includes.pages.report-content>
                        <div class="row">
                            <div class="col-12 text-center h3">LAPORAN KARTU STOK</div>
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
                                        <th>Gudang</th>
                                        <th>
                                            :
                                            {{ count($gudang_ids) == 0 ? 'Semua Gudang' : $gudangs->pluck('nama')->implode(', ') }}
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Produk</th>
                                        <th>
                                            :
                                            {{ count($produk_ids) == 0 ? 'Semua Produk' : $produks->pluck('nama')->implode(', ') }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                @foreach ($cabangs as $cabang)
                                    <div class="fw-bold h5">Cabang {{ $cabang->nama }}</div>
                                    @foreach ($gudangs as $gudang)
                                        <?php
                                        $mutasiStokGudangs = App\Models\System\MutasiStok::query()
                                            ->when($tanggal, function ($query) use ($tanggal) {
                                                return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                                            })
                                            ->where('gudang_id', $gudang->id)
                                            ->where('cabang_id', $cabang->id)
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
                                                    ->with('header')
                                                    ->when($tanggal, function ($query) use ($tanggal) {
                                                        return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                                                    })
                                                    ->where('gudang_id', $gudang->id)
                                                    ->where('produk_id', $produk->id)
                                                    ->where('cabang_id', $cabang->id)
                                                    ->get();
                                                ?>

                                                @if (count($mutasiStoks))
                                                    <div class="ms-4 mt-3">
                                                        <div class="fw-bold">
                                                            {{ $produk->kode }} &mdash; {{ $produk->nama }}
                                                        </div>

                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr class="text-center">
                                                                    <th width="5%">No</th>
                                                                    <th width="35%">No. Dokumen / Tanggal</th>
                                                                    <th width="20%">Qty Masuk</th>
                                                                    <th width="20%">Qty Keluar</th>
                                                                    <th width="20%">Saldo</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($mutasiStoks as $mutasiStok)
                                                                    <?php
                                                                    $mutasiStok->loadMissing('reference.header');
                                                                    ?>

                                                                    @if ($isSaldoAwal)
                                                                        <?php
                                                                        $stokAwal = \App\Utilities\Functions\InventoryFunction::getStok(cabang_id: $cabang->id, produk_id: $mutasiStok->produk_id, gudang_id: $mutasiStok->gudang_id, tanggal: $tanggalAwal, is_tanggal_inclusive: false);
                                                                        ?>

                                                                        <tr>
                                                                            <td class="text-center">
                                                                                {{ $loop->iteration }}
                                                                            </td>
                                                                            <td>Saldo Awal</td>
                                                                            <td></td>
                                                                            <td></td>
                                                                            <td class="text-end">
                                                                                {{ _number($stokAwal) }}
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
                                                                            <a href="{{ $mutasiStok->header?->getRouteShow() }}"
                                                                                target="_blank">
                                                                                {{ $mutasiStok->header?->kode }}
                                                                            </a>
                                                                            <br />
                                                                            {{ $mutasiStok->tanggal }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            {{ $mutasiStok->jumlah > 0 ? _number($mutasiStok->jumlah) : '' }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            {{ $mutasiStok->jumlah > 0 ? '' : _number($mutasiStok->jumlah) }}
                                                                        </td>
                                                                        <td class="text-end">
                                                                            {{ _number($stokAwal) }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                            <tfoot>
                                                                <tr>
                                                                    <th colspan="2" class="text-center">
                                                                        Total Mutasi
                                                                    </th>
                                                                    <th class="text-end">
                                                                        {{ _number($mutasiJumlahMasuk) }}
                                                                    </th>
                                                                    <th class="text-end">
                                                                        {{ _number($mutasiJumlahKeluar) }}
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
                                @endforeach
                            </div>
                        </div>
                    </x-admin::includes.pages.report-content>
                @endif
            </div>
        </div>
    </form>
</div>
