@php
    use App\Utilities\Constants\Const_Umum;
@endphp
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
                    <th>Produk</th>
                    <td>
                        <a href="{{ $obj->produk->getRouteShow() }}">
                            {{ $obj->produk->nama }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>Minimum Pembelian (Pcs)</th>
                    <td> Rp. {{ _number($obj->jumlah_minimum) }}</td>
                </tr>
                <tr>
                    <th>Tambahan Diskon (%)</th>
                    <td> {{ _number($obj->tambahan_diskon) }}%</td>
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
