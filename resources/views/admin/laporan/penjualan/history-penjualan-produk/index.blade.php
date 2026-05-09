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
                                <x-admin::input.tags :id="'produk_ids'" :name="'produk_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Produk::active()"
                                    :placeholder="'- Semua Produk -'" />
                            </div>

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'customer_ids'" :name="'customer_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Customer::active()"
                                    :placeholder="'- Semua Customer -'" />
                            </div>

                            <div class="col-xxl-12 col-sm-12">
                                <x-admin::buttons.report-lihat />
                            </div>
                        </div>
                    </x-admin::includes.pages.report-filter>
                </div>

                @if ($is_lihat)
                    <x-admin::includes.pages.report-content>
                        <div class="row">
                            <div class="col-12 text-center h3">HISTORY PENJUALAN PRODUK</div>
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
                                        <th>Customer</th>
                                        <th>
                                            :
                                            {{ $isSemuaCustomer ? 'Semua Customer' : $customers?->pluck('nama')->implode(', ') }}
                                        </th>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="row">
                            <?php
                            $objs = \App\Models\Penjualan\FakturPenjualanDetail::query()
                                ->with(['produk', 'satuan', 'fakturPenjualan', 'fakturPenjualan.customer', 'header.customer'])
                                ->whereIn('produk_id', $produkIds)
                                ->when($tanggal, function ($query) use ($tanggal) {
                                    return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRangeRelation($query, $tanggal, 'fakturPenjualan', 'tanggal');
                                })
                                ->whereRelation('fakturPenjualan', function ($query) use ($cabangIds, $customerIds) {
                                    return $query->whereIn('cabang_id', $cabangIds)->whereIn('customer_id', $customerIds);
                                })
                                ->get();
                            ?>

                            <div class="col-12 mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="8%">Tanggal</th>
                                            <th width="8%">No. Referensi</th>
                                            <th width="8%">Customer</th>
                                            <th width="15%">Produk</th>
                                            <th width="8%">Qty</th>
                                            <th width="8%">Harga</th>
                                            <th colspan="2">Diskon Item</th>
                                            <th width="8%">Diskon Faktur</th>
                                            <th width="8%">Beban Faktur</th>
                                            <th width="8%">DPP</th>
                                            <th width="8%">PPN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($objs as $obj)
                                            <tr>
                                                <td>{{ _date_format_output($obj->header->tanggal) }}</td>
                                                <td>
                                                    <a href="{{ $obj->header->getRouteShow() }}" target="_blank">
                                                        {{ $obj->header->kode }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ $obj->header->customer->getRouteShow() }}"
                                                        target="_blank">
                                                        {{ $obj->header->customer->nama }}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href="{{ $obj->produk->getRouteShow() }}" target="_blank">
                                                        {{ $obj->produk->nama }}
                                                    </a>
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($obj->jumlah) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($obj->harga_net_satuan) }}
                                                </td>
                                                <td width="5%" class="text-end">
                                                    {{ _number($obj->diskon_satuan_persen) }}%
                                                </td>
                                                <td width="8%" class="text-end">
                                                    {{ _number($obj->diskon_satuan_rupiah) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($obj->diskon_satuan_footer) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($obj->beban_satuan_footer) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($obj->dpp) }}
                                                </td>
                                                <td class="text-end">
                                                    {{ _number($obj->ppn) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-end" colspan="10">TOTAL</th>
                                            <td class="text-end">
                                                {{ _number($objs->sum('dpp')) }}
                                            </td>
                                            <td class="text-end">
                                                {{ _number($objs->sum('ppn')) }}
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
