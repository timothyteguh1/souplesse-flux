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
                            <div class="col-12 text-center h3">PERGERAKAN STOK</div>
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
                            <div class="col-12 mt-3">
                                @foreach ($cabangs as $cabang)
                                    @foreach ($gudangs as $gudang)
                                        <?php
                                        $mutasiStokGudangs = App\Models\System\MutasiStok::query()
                                            ->with(['produk'])
                                            ->whereIn('produk_id', $produkIds)
                                            ->where('cabang_id', $cabang->id)
                                            ->where('gudang_id', $gudang->id)
                                            ->groupBy('produk_id', 'gudang_id')
                                            ->selectRaw('produk_id, gudang_id')
                                            ->get();
                                        ?>

                                        @if (count($mutasiStokGudangs))
                                            <div class="fw-bold">Gudang {{ $gudang->nama }}</div>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="20%">Kode Produk</th>
                                                        <th width="35%">Nama Produk</th>
                                                        <th width="15%" class="text-end">Awal</th>
                                                        <th width="15%" class="text-end">Mutasi</th>
                                                        <th width="15%" class="text-end">Akhir</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($mutasiStokGudangs as $mutasiStokGudang)
                                                        <tr>
                                                            <td>
                                                                <a href="{{ $mutasiStokGudang->produk->getRouteShow() }}"
                                                                    target="_blank">
                                                                    {{ $mutasiStokGudang->produk->kode }}
                                                                </a>
                                                            </td>
                                                            <td>
                                                                {{ $mutasiStokGudang->produk->nama }}
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('admin.laporan.persediaan.kartu-stok', [
                                                                    'tanggal' => _get_default_date_range(true, $tanggalAwal),
                                                                    'cabang_ids' => [$cabang->id],
                                                                    'gudang_ids' => [$gudang->id],
                                                                    'produk_ids' => [$mutasiStokGudang->produk_id],
                                                                ]) }}"
                                                                    target="_blank">
                                                                    <?php
                                                                    $stokAwal = \App\Utilities\Functions\InventoryFunction::getStok(cabang_id: $cabang->id, produk_id: $mutasiStokGudang->produk_id, gudang_id: $mutasiStokGudang->gudang_id, tanggal: $tanggalAwal, is_tanggal_inclusive: false);
                                                                    ?>

                                                                    {{ _number($stokAwal) }}
                                                                </a>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('admin.laporan.persediaan.kartu-stok', [
                                                                    'tanggal' => _get_default_date_range(false, $tanggalAkhir, $tanggalAwal),
                                                                    'cabang_ids' => [$cabang->id],
                                                                    'gudang_ids' => [$gudang->id],
                                                                    'produk_ids' => [$mutasiStokGudang->produk_id],
                                                                ]) }}"
                                                                    target="_blank">
                                                                    <?php
                                                                    $stokMutasi = \App\Utilities\Functions\InventoryFunction::getStokMutasi(cabang_id: $cabang->id, produk_id: $mutasiStokGudang->produk_id, gudang_id: $mutasiStokGudang->gudang_id, tanggal_awal: $tanggalAwal, tanggal_akhir: $tanggalAkhir);
                                                                    ?>

                                                                    {{ _number($stokMutasi) }}
                                                                </a>
                                                            </td>
                                                            <td class="text-end">
                                                                <a href="{{ route('admin.laporan.persediaan.kartu-stok', [
                                                                    'tanggal' => _get_default_date_range(true, $tanggalAkhir),
                                                                    'cabang_ids' => [$cabang->id],
                                                                    'gudang_ids' => [$gudang->id],
                                                                    'produk_ids' => [$mutasiStokGudang->produk_id],
                                                                ]) }}"
                                                                    target="_blank">
                                                                    <?php
                                                                    $stokAkhir = \App\Utilities\Functions\InventoryFunction::getStok(cabang_id: $cabang->id, produk_id: $mutasiStokGudang->produk_id, gudang_id: $mutasiStokGudang->gudang_id, tanggal: $tanggalAkhir);
                                                                    ?>

                                                                    {{ _number($stokAkhir) }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
