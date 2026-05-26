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
                        <button class="dropdown-item" wire:click="approve('{{ $obj->id }}')">
                            <i class="ri-check-line label-icon align-middle fs-16 me-2"></i>
                            Approve
                        </button>
                        <button class="dropdown-item" wire:click="tolak('{{ $obj->id }}')">
                            <i class="ri-close-line label-icon align-middle fs-16 me-2"></i>
                            Tolak
                        </button>
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
                    <th width="20%">Kode</th>
                    <td>{{ $obj->kode }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $obj->tanggal }}</td>
                </tr>
                <tr>
                    <th>Supplier</th>
                    <td>
                        <a href="{{ $obj->supplier->getRouteShow() }}">
                            {{ $obj->supplier->kode }} -
                            {{ $obj->supplier->nama }}
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
                <div class="card-header">
                    <div class="d-sm-flex align-items-center">
                        <h6 class="card-title mb-0">Details</h6>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr class="bg-light">
                                    <th class="text-center" width="10%">#</th>
                                    <th width="20%">Produk</th>
                                    <th width="10%">Model</th>
                                    <th width="10%" class="text-end">Qty</th>
                                    <th width="10%" class="text-end">Harga</th>
                                    <th width="15%" class="text-end" colspan="2">Diskon</th>
                                    <th width="15%" class="text-end">Subtotal</th>
                                    <th width="10%" class="text-end">Note</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obj->details()->with(['produk.modelProduk', 'satuan'])->get() as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            <a href="{{ $detail->produk->getRouteShow() }}">
                                                {{ $detail->produk->nama }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $detail->produk?->modelProduk?->nama }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->jumlah) }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->harga_satuan) }}
                                        </td>
                                        <td class="text-end">{{ _number($detail->diskon_satuan_persen) }}%</td>
                                        <td class="text-end">
                                            {{ _number($detail->diskon_satuan_rupiah) }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->subtotal) }}
                                        </td>
                                        <td>
                                            {{ $detail->keterangan }}
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
                                    <th colspan="8" class="text-end">
                                        Diskon Faktur ({{ _number($obj->diskon_persen) }}%)
                                    </th>
                                    <th class="text-end">
                                        {{ _number($obj->diskon_rupiah) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">DPP</th>
                                    <th class="text-end">
                                        {{ _number($obj->dpp) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="8" class="text-end">PPN ({{ _number($obj->ppn_percent) }}%)</th>
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

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
