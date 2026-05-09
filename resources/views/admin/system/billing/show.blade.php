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
                <tr>
                    <th width="20%">Kode</th>
                    <td>{{ $obj->kode }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ _date_format_output($obj->tanggal) }}</td>
                </tr>
                <tr>
                    <th>Tanggal Jatuh Tempo</th>
                    <td>{{ _date_format_output($obj->tanggal_jatuh_tempo) }}</td>
                </tr>
                <tr>
                    <th>Perusahaan</th>
                    <td>
                        <a href="{{ $obj->perusahaan?->getRouteShow() }}">
                            {{ $obj->perusahaan?->nama }}
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
                    <th>Diskon</th>
                    <td>
                        {{ $obj->diskon_type == App\Utilities\Constants\Const_Umum::DISKON_TYPE_RP ? _number($obj->diskon) : _number($obj->diskon) . ' %' }}
                    </td>
                </tr>
                <tr>
                    <th>Beban Lain</th>
                    <td>{{ _number($obj->beban_lain) }}</td>
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
                                    <th width="30%">Item</th>
                                    <th width="15%" class="text-end">Qty</th>
                                    <th width="15%" class="text-end">Harga</th>
                                    <th width="15%" class="text-end" colspan="2">Diskon</th>
                                    <th width="15%" class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obj->details as $detail)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ $detail->item }}
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
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" class="text-end"> TOTAL</td>
                                    <td class="text-end"> {{ _number($obj->details->sum('subtotal')) }} </td>
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
