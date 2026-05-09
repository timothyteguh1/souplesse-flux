<div>
    @section('title', $obj->nama)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item active">{{ $obj->nama }}</li>
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
                    @if ($obj->canShowHistory())
                        {{-- <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.laporan.pembelian.history-pembelian-produk', [
                            'cabang_ids' => [$obj->cabang_id],
                            'supplier_ids' => [$obj->id],
                        ]) }}"
                            target="_blank" class="dropdown-item">
                            <i class="ri-history-line label-icon align-middle fs-16 me-2"></i>
                            History Pembelian Produk
                        </a> --}}
                    @endif
                </x-slot>
                <x-slot name="body">
                    <div>
                        <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#dataUmum" role="tab">
                                    Data Umum
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabLainLain"
                                    role="tab">Lain-Lain</a>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="dataUmum" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <tbody>
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
                                            <tr>
                                                <th>Nama</th>
                                                <td>{{ $obj->nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>No. Telp Bisnis</th>
                                                <td>{{ $obj->telp }}</td>
                                            </tr>
                                            <tr>
                                                <th>Handphone</th>
                                                <td>{{ $obj->handphone }}</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td>{{ $obj->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jalan</th>
                                                <td>{{ $obj->alamat }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kota</th>
                                                <td>{{ $obj->kota }}</td>
                                            </tr>
                                            <tr>
                                                <th>PKP</th>
                                                <td>{{ $obj->is_pkp ? 'Ya' : 'Tidak' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Information</th>
                                                <td>{!! nl2br(e($obj->payment_info)) !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Keterangan</th>
                                                <td>{!! nl2br(e($obj->keterangan)) !!}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{{ $obj->status }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="tabLainLain" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="20%">Jatuh Tempo</th>
                                                <td width="80%">{{ _number($obj->jatuh_tempo) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Bank</th>
                                                <td>{{ $obj->rekening_bank }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Rekening</th>
                                                <td>{{ $obj->rekening_nomor }}</td>
                                            </tr>
                                            <tr>
                                                <th>Atas Nama</th>
                                                <td>{{ $obj->rekening_nama }}</td>
                                            </tr>
                                            <tr>
                                                <th>NPWP</th>
                                                <td>{{ $obj->npwp_kode_format }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end tab-pane-->
                        </div>
                    </div>
                </x-slot>
            </x-admin::includes.pages.show-attributes-card>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
