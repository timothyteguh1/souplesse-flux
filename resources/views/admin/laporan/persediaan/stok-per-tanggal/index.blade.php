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
                                <x-admin::input.date :name="'tanggal'" placeholder="Masukkan Tanggal" />
                            </div>

                            <div class="col-xxl-8 col-sm-6">
                                <x-admin::input.tags :id="'gudang_ids'" :name="'gudang_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Gudang::user()"
                                    :placeholder="'- Semua Gudang -'" />
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
                            <div class="col-12 text-center h3">STOK PER TANGGAL</div>
                        </div>

                        <div class="row">
                            <div class="col-12">
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
                                </table>
                            </div>

                            <div class="col-12 mt-3">
                                @foreach ($cabangs as $cabang)
                                    <div class="fw-bold h5">Cabang {{ $cabang->nama }}</div>
                                    @foreach ($gudangs as $gudang)
                                        <?php
                                        $mutasiStokGudangs = App\Models\System\MutasiStok::query()
                                            ->with(['produk'])
                                            ->where('tanggal', '<=', $tanggalCarbon)
                                            ->where('gudang_id', $gudang->id)
                                            ->where('cabang_id', $cabang->id)
                                            ->groupBy('produk_id')
                                            ->selectRaw('produk_id, sum(jumlah) as jumlah')
                                            ->get();
                                        ?>

                                        @if (count($mutasiStokGudangs))
                                            <div class="fw-bold">Gudang {{ $gudang->nama }}</div>

                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th width="25%">Kode Produk</th>
                                                        <th width="45%">Nama Produk</th>
                                                        <th width="30%" class="text-end">Jumlah</th>
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
                                                                    'tanggal' => _get_default_date_range(true, $tanggal),
                                                                    'gudang_ids' => [$gudang->id],
                                                                    'produk_ids' => [$mutasiStokGudang->produk_id],
                                                                ]) }}"
                                                                    target="_blank">
                                                                    {{ _number($mutasiStokGudang->jumlah) }}
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
