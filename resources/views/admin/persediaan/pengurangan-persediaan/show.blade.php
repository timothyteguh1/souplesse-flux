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
                    <th>Gudang</th>
                    <td>
                        <a href="{{ $obj->gudang->getRouteShow() }}">
                            {{ $obj->gudang->kode }} - {{ $obj->gudang->nama }}
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
                                    <th width="20%" class="text-uppercase">Produk</th>
                                    <th width="15%" class="text-uppercase">Satuan</th>
                                    <th width="15%" class="text-uppercase">Expired Date</th>
                                    <th width="15%" class="text-uppercase">No Batch</th>
                                    <th width="10%" class="text-uppercase text-end">Qty Kurang</th>
                                    <th width="10%" class="text-uppercase text-end">DPP</th>
                                    <th width="15%" class="text-uppercase text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail)
                                    <tr>
                                        <td>
                                            <a href="{{ $detail->produk->getRouteShow() }}">
                                                {{ $detail->produk->kode }} - {{ $detail->produk->nama }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ $detail->satuan->getRouteShow() }}">
                                                {{ $detail->satuan->kode }} - {{ $detail->satuan->nama }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $detail->expired_date }}
                                        </td>
                                        <td>
                                            {{ $detail->no_batch }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->jumlah) }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->harga_satuan) }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-end" colspan="6">Total Nilai Persediaan yang Berkurang</th>
                                    <th class="text-end">{{ _number($obj->grandtotal) }}</th>
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
