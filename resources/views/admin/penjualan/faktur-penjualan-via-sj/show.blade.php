@php
    use App\Utilities\Constants\Const_Status;
    use App\Utilities\Constants\Const_Umum;
@endphp

<div>
    @section('title', $obj->kode)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a></li>
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
                    @if ($obj->canPrint())
                        <div class="dropdown-divider"></div>
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
                    <th width="20%">Surat Jalan</th>
                    <td>
                        <a href="{{ $obj->suratJalan->getRouteShow() }}">
                            {{ $obj->suratJalan->kode }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $obj->tanggal }}</td>
                </tr>
                <tr>
                    <th>Tanggal Jatuh Tempo</th>
                    <td>{{ $obj->tanggal_jatuh_tempo }}</td>
                </tr>
                <tr>
                    <th>Customer</th>
                    <td>
                        <a href="{{ $obj->customer->getRouteShow() }}">
                            {{ $obj->customer->kode }} &mdash; {{ $obj->customer->nama }}
                        </a>
                    </td>
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
                <div>
                    <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                        role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#dataDetail" role="tab">Detail</a>
                        </li>
                        @if (count($obj->fakturPenjualanBebans))
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabBeban" role="tab">Beban</a>
                            </li>
                        @endif
                    </ul>
                </div>

                <div class="pt-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="dataDetail" role="tabpanel">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center">
                                    <h6 class="card-title mb-0">Detail Produk</h6>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr class="bg-light">
                                                <th width="5%" class="text-uppercase">No</th>
                                                <th width="15%" class="text-uppercase">Nama Produk</th>
                                                <th width="10%" class="text-uppercase">Satuan</th>
                                                <th width="10%" class="text-uppercase text-end">Qty</th>
                                                <th width="10%" class="text-uppercase text-end">Harga Jual</th>
                                                <th width="10%" class="text-end" colspan="2">Diskon</th>
                                                <th width="10%" class="text-end">Detail Diskon</th>
                                                <th width="10%" class="text-uppercase text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ $detail->produk->getRouteShow() }}">
                                                            {{ $detail->produk->nama }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $detail->satuan->getRouteShow() }}">
                                                            {{ $detail->satuan->nama }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($detail->jumlah) }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($detail->harga_satuan) }}
                                                    </td>
                                                    <td class="text-end">{{ _number($detail->diskon_satuan_persen) }}%
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($detail->diskon_satuan_rupiah) }}
                                                    </td>
                                                    <td>
                                                        @if ($detail->diskon_satuan_1 != 0)
                                                            Diskon 1:
                                                            @if ($detail->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_RP)
                                                                {{ $detail->diskon_satuan_type_1 . '. ' . _number($detail->diskon_satuan_1) }}
                                                            @elseif ($detail->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_PERCENT)
                                                                {{ _number($detail->diskon_satuan_1) . $detail->diskon_satuan_type_1 }}
                                                            @endif
                                                        @endif
                                                        @if ($detail->diskon_satuan_2 != 0)
                                                            <br>
                                                            Diskon 2:
                                                            @if ($detail->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_RP)
                                                                {{ $detail->diskon_satuan_type_2 . '. ' . _number($detail->diskon_satuan_2) }}
                                                            @elseif ($detail->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_PERCENT)
                                                                {{ _number($detail->diskon_satuan_2) . $detail->diskon_satuan_type_2 }}
                                                            @endif
                                                        @endif
                                                        @if ($detail->diskon_satuan_3 != 0)
                                                            <br>
                                                            Diskon 3:
                                                            @if ($detail->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_RP)
                                                                {{ $detail->diskon_satuan_type_3 . '. ' . _number($detail->diskon_satuan_3) }}
                                                            @elseif ($detail->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_PERCENT)
                                                                {{ _number($detail->diskon_satuan_3) . $detail->diskon_satuan_type_3 }}
                                                            @endif
                                                        @endif
                                                        @if ($detail->diskon_satuan_4 != 0)
                                                            <br>
                                                            Diskon 4:
                                                            @if ($detail->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_RP)
                                                                {{ $detail->diskon_satuan_type_4 . '. ' . _number($detail->diskon_satuan_4) }}
                                                            @elseif ($detail->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_PERCENT)
                                                                {{ _number($detail->diskon_satuan_4) . $detail->diskon_satuan_type_4 }}
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($detail->subtotal) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabBeban" role="tabpanel">
                            <div class="card-header">
                                <div class="d-sm-flex align-items-center">
                                    <h6 class="card-title mb-0">Detail Beban</h6>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr class="bg-light">
                                                <th width="5%" class="text-uppercase">No</th>
                                                <th width="50%" class="text-uppercase">Beban</th>
                                                <th width="45%" class="text-uppercase text-end">Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obj->fakturPenjualanBebans()->with(['beban'])->get() as $detail)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <a href="{{ $detail->beban?->getRouteShow() }}">
                                                            {{ $detail->beban?->nama }}
                                                        </a>
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($detail->jumlah) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-md-6 offset-md-6">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th class="col-4">Total</th>
                                    <th class="col-6 text-end">
                                        {{ _number($obj->total) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="col-4">Diskon Faktur ({{ _number($obj->diskon_persen) }}%)</th>
                                    <th class="col-6 text-end">
                                        {{ _number($obj->diskon_rupiah) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>DPP</th>
                                    <th class="text-end">
                                        {{ _number($obj->dpp) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>PPN</th>
                                    <th class="text-end">
                                        {{ _number($obj->ppn) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="col-4">Total Beban</th>
                                    <th class="col-6 text-end">
                                        {{ _number($obj->total_beban) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>Grand Total</th>
                                    <th class="text-end">
                                        {{ _number($obj->grandtotal) }}
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if ($obj->jenis_transaksi == App\Utilities\Constants\Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS)
                <div class="card">
                    <div class="card-header">
                        <div class="d-sm-flex align-items-center">
                            <h6 class="card-title mb-0">Pembayaran</h6>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr class="bg-light">
                                        <th class="text-center" width="10%">No</th>
                                        <th width="35%">Kas</th>
                                        <th width="35%">Keterangan</th>
                                        <th width="20%" class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($obj->pembayarans()->with(['kas'])->get() as $detail)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>
                                                <a href="{{ $detail->kas->getRouteShow() }}">
                                                    {{ $detail->kas->nama }}
                                                </a>
                                            </td>
                                            <td>{{ $detail->keterangan }}</td>
                                            <td class="text-end">
                                                {{ _number($detail->jumlah) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Grand Total</th>
                                        <th class="text-end">
                                            {{ _number($obj->grandtotal) }}
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <x-admin::includes.pages.show-footer-card :obj="$obj" :show-stock-card="true" />
</div>
