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
                <tr>
                    <th width="20%">Kode</th>
                    <td>{{ $obj->kode }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $obj->nama }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{!! nl2br(e($obj->alamat)) !!}</td>
                </tr>
                <tr>
                    <th>Kota</th>
                    <td>{{ $obj->kota }}</td>
                </tr>
                <tr>
                    <th>Telp</th>
                    <td>{{ $obj->telp }}</td>
                </tr>
                <tr>
                    <th>E-mail Address</th>
                    <td>{{ $obj->email }}</td>
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
                    <th>Logo</th>
                    <td>
                        @if ($obj->getFirstMediaUrl())
                            <a href="{{ $obj->getFirstMediaUrl() }}" target="_blank">
                                <img
                                    src="{{ $obj->getFirstMediaUrl('default', 'thumbnail') }}"
                                    height="200px"
                                    class="rounded"
                                />
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $obj->status }}</td>
                </tr>
            </x-admin::includes.pages.show-attributes-card>

            <div class="card p-3">
                <div class="row">
                    <div class="col-md-6 col-12">
                        <h5>Data KTP Pemilik</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <th width="40%">Nama KTP Pemilik</th>
                                        <td>{{ $obj->ktp_nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor KTP Pemilik</th>
                                        <td>{{ $obj->ktp_nomor }}</td>
                                    </tr>
                                    <tr>
                                        <th>Foto KTP Pemilik</th>
                                        @if ($obj->getMedia('ktp_foto'))
                                            <td>
                                                <a
                                                    href="{{ $obj->getMedia('ktp_foto')->first()?->getUrl() }}"
                                                    target="_blank"
                                                >
                                                    <img
                                                        src="{{ $obj->getMedia('ktp_foto')->first()?->getUrl() }}"
                                                        height="200px"
                                                        class="rounded"
                                                    />
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <h5>Data NPWP Pemilik</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <th width="40%">Nama NPWP Pemilik</th>
                                        <td>{{ $obj->npwp_nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor NPWP Pemilik</th>
                                        <td>{{ $obj->npwp_nomor }}</td>
                                    </tr>
                                    <tr>
                                        <th>Foto NPWP Pemilik</th>
                                        @if ($obj->getMedia('npwp_foto'))
                                            <td>
                                                <a
                                                    href="{{ $obj->getMedia('npwp_foto')->first()?->getUrl() }}"
                                                    target="_blank"
                                                >
                                                    <img
                                                        src="{{ $obj->getMedia('npwp_foto')->first()?->getUrl() }}"
                                                        height="200px"
                                                        class="rounded"
                                                    />
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6 col-12">
                        <h5>Data Surat Ijin Apotek (SIA)</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <th width="40%">Nama SIA</th>
                                        <td>{{ $obj->sia_nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor SIA</th>
                                        <td>{{ $obj->sia_nomor }}</td>
                                    </tr>
                                    <tr>
                                        <th>Berlaku Sampai</th>
                                        <td>{{ _date_format_output($obj->sia_berlaku) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Foto SIA</th>
                                        @if ($obj->getMedia('sia_foto'))
                                            <td>
                                                <a
                                                    href="{{ $obj->getMedia('sia_foto')->first()?->getUrl() }}"
                                                    target="_blank"
                                                >
                                                    <img
                                                        src="{{ $obj->getMedia('sia_foto')->first()?->getUrl() }}"
                                                        height="200px"
                                                        class="rounded"
                                                    />
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <h5>Data Surat Izin Praktek Apotek (SIPA)</h5>
                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <th width="40%">Nama SIPA</th>
                                        <td>{{ $obj->sipa_nama }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor SIPA</th>
                                        <td>{{ $obj->sipa_nomor }}</td>
                                    </tr>
                                    <tr>
                                        <th>Berlaku Sampai</th>
                                        <td>{{ _date_format_output($obj->sipa_berlaku) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Foto SIPA</th>
                                        @if ($obj->getMedia('sipa_foto'))
                                            <td>
                                                <a
                                                    href="{{ $obj->getMedia('sipa_foto')->first()?->getUrl() }}"
                                                    target="_blank"
                                                >
                                                    <img
                                                        src="{{ $obj->getMedia('sipa_foto')->first()?->getUrl() }}"
                                                        height="200px"
                                                        class="rounded"
                                                    />
                                                </a>
                                            </td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
