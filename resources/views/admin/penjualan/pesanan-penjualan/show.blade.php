@php
    use App\Utilities\Constants\Const_Umum;
@endphp
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
                    @if ($obj->canKonfirmasi())
                        {{-- <button class="dropdown-item" wire:click="approve('{{ $obj->id }}')">
                            <i class="ri-check-line label-icon align-middle fs-16 me-2"></i>
                            Approve
                        </button>
                        <button class="dropdown-item" wire:click="tolak('{{ $obj->id }}')">
                            <i class="ri-close-line label-icon align-middle fs-16 me-2"></i>
                            Tolak
                        </button> --}}
                        <button class="dropdown-item" wire:click="tutup('{{ $obj->id }}')">
                            <i class="ri-close-line label-icon align-middle fs-16 me-2"></i>
                            Tutup Pesanan
                        </button>
                    @endif

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
                    <th width="20%">Jenis Transaksi</th>
                    <td>{{ $obj->jenis_transaksi }}</td>
                </tr>
                <tr>
                    <th>Kode</th>
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
                    <th>PKP</th>
                    <td>{{ $obj->is_pkp ? 'Ya' : 'Tidak' }}</td>
                </tr>
                <tr>
                    <th>Include PPN</th>
                    <td>{{ $obj->is_include_ppn ? 'Ya' : 'Tidak' }}</td>
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
                                    <th class="text-center" width="5%">#</th>
                                    <th width="25%">Produk</th>
                                    <th width="10%" class="text-end">Qty</th>
                                    <th width="10%" class="text-end">Harga</th>
                                    <th width="15%" class="text-center" colspan="2">Diskon per Qty</th>
                                    <th width="10%">Detail Diskon</th>
                                    <th width="20%" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ $detail->produk->getRouteShow() }}">
                                                {{ $detail->produk->nama }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->jumlah) }}
                                            <a href="{{ optional($detail->satuan)->getRouteShow() }}">
                                                {{ optional($detail->satuan)->nama }}
                                            </a>
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->harga_satuan) }}
                                        </td>
                                        <td class="text-end">{{ _number($detail->diskon_satuan_persen) }}%</td>
                                        <td class="text-end">
                                            {{ _number($detail->diskon_satuan_rupiah) }}
                                        </td>
                                        <td>
                                            @for ($i = 1; $i <= 4; $i++)
                                                @php
                                                    $diskon = "diskon_satuan_$i";
                                                    $type = "diskon_satuan_type_$i";
                                                @endphp

                                                @if ($detail->$diskon != 0)
                                                    @if ($i > 1)
                                                        <br>
                                                    @endif
                                                    Disk {{ $i }}:
                                                    @if ($detail->$type == Const_Umum::DISKON_TYPE_RP)
                                                        {{ $detail->$type . '. ' . _number($detail->$diskon) }}
                                                    @else
                                                        {{ _number($detail->$diskon) . $detail->$type }}
                                                    @endif
                                                @endif
                                            @endfor
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="7" class="text-end">Total</th>
                                    <th class="text-end">
                                        {{ _number($obj->total) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-end">
                                        Diskon Faktur ({{ _number($obj->diskon_persen) }}%)
                                    </th>
                                    <th class="text-end">
                                        {{ _number($obj->diskon_rupiah) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-end">DPP</th>
                                    <th class="text-end">
                                        {{ _number($obj->dpp) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-end">PPN ({{ _number($obj->ppn_percent) }}%)</th>
                                    <th class="text-end">
                                        {{ _number($obj->ppn) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="7" class="text-end">Grand Total</th>
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

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
