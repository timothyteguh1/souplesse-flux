<div>
    @section('title', "Detail {$menuTitle}")

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $model::routeIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item active">Detail {{ $menuTitle }}</li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-admin::includes.pages.show-attributes-card :show-action-buttons="false">
                <tr>
                    <th width="20%">Tanggal</th>
                    <td>
                        {{ $obj->tanggal }}
                    </td>
                </tr>
                <tr>
                    <th>Cabang</th>
                    <td>
                        <a href="{{ $obj->cabang?->getRouteShow() }}">
                            {{ $obj->cabang?->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Jenis</th>
                    <td>{{ $obj->jenis }}</td>
                </tr>
                <tr>
                    <th>Vendor</th>
                    <td>
                        <a href="{{ $obj->vendor?->getRouteShow() }}">
                            {{ $obj->vendor?->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Reference</th>
                    <td>
                        <a href="{{ $obj->header?->getRouteShow() }}">
                            {{ $obj->header?->kode }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Jenis Transaksi</th>
                    <td>{{ $obj->jenis_transaksi }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ _number($obj->jumlah) }}</td>
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
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
