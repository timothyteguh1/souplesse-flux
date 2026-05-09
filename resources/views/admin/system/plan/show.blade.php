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
                <tr>
                    <th width="20%">Kode</th>
                    <td>{{ $obj->kode }}</td>
                </tr>
                <tr>
                    <th>Nama</th>
                    <td>{{ $obj->nama }}</td>
                </tr>
                <tr>
                    <th>Jumlah Cabang</th>
                    <td>{{ _number($obj->jumlah_cabang) }}</td>
                </tr>
                <tr>
                    <th>Jumlah User</th>
                    <td>{{ _number($obj->jumlah_user) }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>{{ _number($obj->harga) }}</td>
                </tr>
                <tr>
                    <th>Masa Aktif Hari</th>
                    <td>{{ _number($obj->masa_aktif_hari) }}</td>
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

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
