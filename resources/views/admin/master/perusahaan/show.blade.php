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
                    <th>Owner</th>
                    <td>
                        @if ($obj->user)
                            <a href="{{ $obj->user->getRouteShow() }}">
                                {{ $obj->user->name }}
                            </a>
                        @endif
                    </td>
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
                    <th>Plan</th>
                    <td>
                        <a href="{{ $obj->plan->getRouteShow() }}">
                            {{ $obj->plan->nama }}
                        </a>
                    </td>
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
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
