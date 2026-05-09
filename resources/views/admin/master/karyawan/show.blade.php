<div>
    @section('title', $obj->nama)

    @section('breadcrumb')
        <li class="breadcrumb-item">
            <a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a>
        </li>
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
                    <th>User</th>
                    <td>
                        @if ($obj->user)
                            <a href="{{ $obj->user->getRouteShow() }}">
                                {{ $obj->user->name }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>No KTP</th>
                    <td>{{ $obj->no_ktp }}</td>
                </tr>
                <tr>
                    <th>Tanggal Masuk</th>
                    <td>{{ _date_format_output($obj->tanggal_masuk) }}</td>
                </tr>
                <tr>
                    <th>Level</th>
                    <td>{{ $obj->level }}</td>
                </tr>
                <tr>
                    <th>Komisi (%)</th>
                    <td>{{ _number($obj->komisi) }}%</td>
                </tr>
                <tr>
                    <th>Telp</th>
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
                    <th>Internal Note</th>
                    <td>{!! nl2br(e($obj->keterangan)) !!}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $obj->status }}</td>
                </tr>
            </x-admin::includes.pages.show-attributes-card>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
