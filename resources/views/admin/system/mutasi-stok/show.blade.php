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
                    <th>Gudang</th>
                    <td>
                        <a href="{{ $obj->gudang?->getRouteShow() }}">
                            {{ $obj->gudang?->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Produk</th>
                    <td>
                        <a href="{{ $obj->produk?->getRouteShow() }}">
                            {{ $obj->produk?->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Satuan</th>
                    <td>
                        <a href="{{ $obj->satuan?->getRouteShow() }}">
                            {{ $obj->satuan?->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Satuan Transaksi</th>
                    <td>
                        <a href="{{ $obj->satuanTransaksi?->getRouteShow() }}">
                            {{ $obj->satuanTransaksi?->nama }}
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
                    <th>Expired Date</th>
                    <td>{{ $obj->expired_date }}</td>
                </tr>
                <tr>
                    <th>No Batch</th>
                    <td>{{ $obj->no_batch }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>{{ _number($obj->jumlah) }}</td>
                </tr>
                <tr>
                    <th>Jumlah Transaksi</th>
                    <td>{{ _number($obj->jumlah_transaksi) }}</td>
                </tr>
                <tr>
                    <th>Harga</th>
                    <td>{{ _number($obj->harga) }}</td>
                </tr>
                <tr>
                    <th>Harga Transaksi</th>
                    <td>{{ _number($obj->harga_transaksi) }}</td>
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
