@php
    use App\Utilities\Constants\Const_Umum;
@endphp
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

                            <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'customer_ids'" :name="'customer_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Customer::active()"
                                    :placeholder="'- Semua Customer -'" />
                            </div>


                            {{-- <div class="col-xxl-4 col-sm-6">
                                <x-admin::input.tags :id="'supplier_ids'" :name="'supplier_ids'" :options="\App\Utilities\SelectHelpers\Master\SH_Supplier::active()"
                                    :placeholder="'- Semua Supplier -'" />
                            </div> --}}

                            <div class="col-xxl-12 col-sm-12">
                                <x-admin::buttons.report-lihat />
                            </div>
                        </div>
                    </x-admin::includes.pages.report-filter>
                </div>

                @if ($is_lihat)
                    <x-admin::includes.pages.report-content>
                        <div class="row">
                            <div class="col-12 text-center h3">PENJUALAN</div>
                            <div class="col-12 my-3">
                                <table>
                                    {{-- <tr>
                                        <th width="100px">Cabang</th>
                                        <th>: {{ $cabangs?->pluck('nama')->implode(', ') }}</th>
                                    </tr> --}}
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
                            $objs = App\Models\Penjualan\FakturPenjualan::query()
                                ->when($tanggal, function ($query) use ($tanggal) {
                                    return \App\Utilities\QueryHelpers\QH_DateTime::scopeDateRange($query, $tanggal, 'tanggal');
                                })
                                ->whereIn('cabang_id', $cabangIds)
                                ->whereIn('customer_id', $customerIds)
                                ->with(['details.produk', 'details.satuan'])
                                ->get();
                            ?>

                            <div class="col-12 mt-3">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr class="text-center">
                                            <th width="10%">Tanggal</th>
                                            <th width="10%">No. Referensi</th>
                                            <th width="10%">Produk</th>
                                            <th width="5%">Satuan</th>
                                            <th width="10%">Qty</th>
                                            <th width="10%">Harga Jual</th>
                                            <th width="10%" colspan="2">Diskon per Qty</th>
                                            <th width="10%">Detail Diskon
                                            </th>
                                            <th width="10%">Subtotal</th>
                                            <th width="10%">Total</th>
                                            <th width="10%" colspan="2">Diskon Faktur </th>
                                            <th width="10%">DPP</th>
                                            <th width="10%">PPN</th>
                                            <th width="10%">Total Beban</th>
                                            <th width="10%">Grandtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="align-middle">

                                        @foreach ($objs as $obj)
                                            @php
                                                $details = $obj->details;
                                                $rowspan = $details->count();
                                            @endphp

                                            @foreach ($details as $index => $item)
                                                <tr>
                                                    {{-- TANGGAL --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-center">
                                                            {{ _date_format_output($obj->tanggal) }}
                                                        </td>
                                                    @endif

                                                    {{-- KODE --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-center">
                                                            <a href="{{ $obj->getRouteShow() }}" target="_blank">
                                                                {{ $obj->kode }}
                                                            </a>
                                                        </td>
                                                    @endif

                                                    {{-- PRODUK --}}
                                                    <td>
                                                        <a href="{{ $item->produk->getRouteShow() }}" target="_blank">
                                                            {{ $item->produk->nama }}
                                                        </a>
                                                    </td>

                                                    {{-- SATUAN --}}
                                                    <td>
                                                        <a href="{{ $item->satuan->getRouteShow() }}" target="_blank">
                                                            {{ $item->satuan->nama }}
                                                        </a>
                                                    </td>

                                                    <td class="text-end">{{ _number($item->jumlah) }}</td>
                                                    <td class="text-end">{{ _number($item->harga_satuan) }}</td>
                                                    <td class="text-end">{{ _number($item->diskon_satuan_persen) }}%
                                                    </td>
                                                    <td class="text-end">{{ _number($item->diskon_satuan_rupiah) }}
                                                    </td>

                                                    {{-- DISKON BERTINGKAT --}}
                                                    <td>
                                                        @for ($i = 1; $i <= 4; $i++)
                                                            @php
                                                                $diskon = "diskon_satuan_$i";
                                                                $type = "diskon_satuan_type_$i";
                                                            @endphp

                                                            @if ($item->$diskon != 0)
                                                                @if ($i > 1)
                                                                    <br>
                                                                @endif
                                                                Disk {{ $i }}:
                                                                @if ($item->$type == Const_Umum::DISKON_TYPE_RP)
                                                                    {{ $item->$type . '. ' . _number($item->$diskon) }}
                                                                @else
                                                                    {{ _number($item->$diskon) . $item->$type }}
                                                                @endif
                                                            @endif
                                                        @endfor
                                                    </td>

                                                    <td class="text-end">{{ _number($item->subtotal) }}</td>

                                                    {{-- TOTAL --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->total) }}
                                                        </td>
                                                    @endif

                                                    {{-- DISKON FAKTUR --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->diskon_persen) }}%
                                                        </td>
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->diskon_rupiah) }}
                                                        </td>
                                                    @endif

                                                    {{-- DPP --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->dpp) }}
                                                        </td>
                                                    @endif

                                                    {{-- PPN --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->ppn) }}
                                                        </td>
                                                    @endif

                                                    {{-- TOTAL BEBAN --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->total_beban) }}
                                                        </td>
                                                    @endif

                                                    {{-- GRAND TOTAL --}}
                                                    @if ($index === 0)
                                                        <td rowspan="{{ $rowspan }}" class="text-end">
                                                            {{ _number($obj->grandtotal) }}
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                            <tr class="table-secondary">
                                                <td colspan="17"></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center" colspan="16">GRANDTOTAL</th>
                                            <td class="text-end">
                                                {{ _number($objs->sum('grandtotal')) }}
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
