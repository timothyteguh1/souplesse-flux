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
                                <x-admin::input.date-range :name="'tanggal'" placeholder="Masukkan Tanggal" />
                            </div>

                            <div class="col-xxl-8 col-sm-6">
                                <x-admin::input.tags :id="'supplier_ids'" :name="'supplier_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Supplier::active()"
                                    :placeholder="'- Semua Supplier -'" />
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
                            <div class="col-12 text-center h3 text-uppercase">FAKTUR PEMBELIAN</div>
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
                                        <th>Supplier</th>
                                        <th>
                                            :
                                            {{ $isSemuaSupplier ? 'Semua Supplier' : $suppliers?->pluck('nama')->implode(', ') }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            $objs = App\Models\Pembelian\FakturPembelian::query()
                                ->when($tanggal, function ($query) use ($tanggal) {
                                    return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                                })
                                ->whereIn('cabang_id', $cabangIds)
                                ->whereIn('supplier_id', $supplierIds)
                                ->get();
                            $grandtotal = 0;
                            ?>

                            <div class="col-12 mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="15%">Tanggal</th>
                                            <th width="15%">No. Referensi</th>
                                            <th width="15%">Produk</th>
                                            <th width="10%" class="text-end">Qty</th>
                                            <th width="10%">Satuan</th>
                                            <th width="10%">Harga Beli Net</th>
                                            <th width="10%">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($objs as $obj)
                                            @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $item)
                                                <tr>
                                                    <td>
                                                        {{ _date_format_output($obj->tanggal) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ $obj->getRouteShow() }}" target="_blank">
                                                            {{ $obj->kode }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $item->produk->getRouteShow() }}" target="_blank">
                                                            {{ $item->produk->nama }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($item->jumlah) }}
                                                    </td>
                                                    <td>
                                                        <a href="{{ $item->satuan->getRouteShow() }}" target="_blank">
                                                            {{ $item->satuan->nama }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($item->harga_net_satuan) }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($item->subtotal) }}
                                                    </td>
                                                </tr>

                                                <?php
                                                $grandtotal += $item->subtotal;
                                                ?>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="6">Total</th>
                                            <td class="text-end">
                                                {{ _number($grandtotal) }}
                                            </td>
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
