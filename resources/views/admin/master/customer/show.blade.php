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
                        <a href="{{ route('admin.laporan.penjualan.history-penjualan-produk', [
                            'cabang_ids' => [$obj->cabang_id],
                            'customer_ids' => [$obj->id],
                        ]) }}"
                            target="_blank" class="dropdown-item">
                            <i class="ri-history-line label-icon align-middle fs-16 me-2"></i>
                            History Penjualan Produk
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
                                <a class="nav-link" data-bs-toggle="tab" href="#tabPajak" role="tab">Pajak</a>
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
                                                <th>Blacklist</th>
                                                <td>{{ $obj->is_blacklist ? 'Ya' : 'Tidak' }}</td>
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
                                                <th>Status</th>
                                                <td>{{ $obj->status }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabPajak" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="20%">NPWP</th>
                                                <td width="80%">{{ $obj->npwp_kode_format }}</td>
                                            </tr>
                                            <tr>
                                                <th>NIK</th>
                                                <td>{{ $obj->npwp_nik }}</td>
                                            </tr>
                                            <tr>
                                                <th>Wajib Pajak</th>
                                                <td>{{ $obj->npwp_wajib_pajak }}</td>
                                            </tr>
                                            <tr>
                                                <th>Jalan</th>
                                                <td>{{ $obj->npwp_alamat }}</td>
                                            </tr>
                                            <tr>
                                                <th>Kota</th>
                                                <td>{{ $obj->npwp_kota }}</td>
                                            </tr>
                                            <tr>
                                                <th>K. Pos</th>
                                                <td>{{ $obj->npwp_kode_pos }}</td>
                                            </tr>
                                            <tr>
                                                <th>Provinsi</th>
                                                <td>{{ $obj->npwp_provinsi }}</td>
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
                                                <th>Limit Piutang</th>
                                                <td>{{ _number($obj->limit_piutang) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <div class="table-responsive">
                                    <h5>Data Pembayaran Bank</h5>
                                    <table class="table table-bordered table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="20%">Bank</th>
                                                <td width="80%">{{ $obj->rekening_bank }}</td>
                                            </tr>
                                            <tr>
                                                <th>Nomor Rekening</th>
                                                <td>{{ $obj->rekening_nomor }}</td>
                                            </tr>
                                            <tr>
                                                <th>Atas Nama</th>
                                                <td>{{ $obj->rekening_nama }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <hr>
                                <h5>Diskon Customer</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr class="bg-light">
                                                <th class="text-center" width="10%">#</th>
                                                <th width="50%">Metode Pembayaran</th>
                                                <th width="40%" class="text-end">Diskon (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($obj->customerDiskons as $detail)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $detail->metode_pembayaran }}
                                                    </td>
                                                    <td class="text-end">
                                                        {{ _number($detail->diskon) }}
                                                    </td>
                                                </tr>
                                            @endforeach
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
