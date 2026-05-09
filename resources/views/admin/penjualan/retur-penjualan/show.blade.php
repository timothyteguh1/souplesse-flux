<div>
    @section('title', $obj->kode)

    @section('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a>
        </li>
        <li class="breadcrumb-item active">{{ $obj->kode }}</li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-admin::includes.pages.show-attributes-card :obj="$obj" :title="$menuTitle">
                <x-slot name="actions">
                    <div class="dropdown-divider"></div>
                    @if ($obj->canPrint())
                        <button class="dropdown-item" wire:click="print">
                            <i class="ri-printer-line label-icon align-middle fs-16 me-2"></i>
                            Print
                        </button>
                    @endif
                </x-slot>
                {{-- <tr>
                    <th width="20%">Cabang</th>
                    <td>
                        <a href="{{ $obj->cabang->getRouteShow() }}">
                            {{ $obj->cabang->nama }}
                        </a>
                    </td>
                </tr> --}}
                <tr>
                    <th width="20%">Kode</th>
                    <td>{{ $obj->kode }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $obj->tanggal }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>
                        <a href="{{ $obj->customer->getRouteShow() }}">
                            {{ $obj->customer->kode }} -
                            {{ $obj->customer->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Gudang</th>
                    <td>
                        <a href="{{ $obj->gudang->getRouteShow() }}">
                            {{ $obj->gudang->kode }} -
                            {{ $obj->gudang->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>PKP</th>
                    <td>{{ $obj->is_pkp ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th>Include PPN</th>
                    <td>{{ $obj->is_include_ppn ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th>PPN Percent</th>
                    <td>{{ _number($obj->ppn_percent) }}%</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{!! nl2br(e($obj->keterangan)) !!}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $obj->status }}</td>
                </tr>
            </x-admin::includes.pages.show-attributes-card>

            <div class="card">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center">
                        <h6 class="card-title mb-0">Details</h6>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr class="bg-light text-uppercase">
                                    <th width="5%">No</th>
                                    <th width="25%">No. Faktur Penjualan</th>
                                    <th width="10%">Tanggal Faktur</th>
                                    <th width="10%">Produk</th>
                                    <th width="10%" class="text-end">Harga Beli Satuan</th>
                                    <th width="15%" class="text-end" colspan="2">Diskon per Qty
                                    </th>
                                    <th width="10%" class="text-end">Qty</th>
                                    <th width="10%" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obj->details()->with(['fakturPenjualanDetail.header', 'produk', 'satuan'])->get() as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ $detail->fakturPenjualanDetail->header->getRouteShow() }}">
                                                {{ $detail->fakturPenjualanDetail->header->kode }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ _date_format_output($detail->fakturPenjualanDetail->header->tanggal) }}
                                        </td>
                                        <td>
                                            <a href="{{ $detail->produk->getRouteShow() }}">
                                                {{ $detail->produk->nama }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->harga_satuan) }}
                                        </td>
                                        <td class="text-end">{{ _number($detail->diskon_satuan_persen) }}%</td>
                                        <td class="text-end">
                                            {{ _number($detail->diskon_satuan_rupiah) }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->jumlah) }}
                                            <a href="{{ optional($detail->satuan)->getRouteShow() }}">
                                                {{ optional($detail->satuan)->nama }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-end">Total</th>
                                    <th class="text-end">
                                        {{ _number($obj->total) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">DPP</th>
                                    <th class="text-end">
                                        {{ _number($obj->dpp) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">PPN</th>
                                    <th class="text-end">
                                        {{ _number($obj->ppn) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">Grand Total</th>
                                    <th class="text-end">
                                        {{ _number($obj->grandtotal) }}
                                    </th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" :show-stock-card="true" />
</div>
