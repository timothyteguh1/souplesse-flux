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
                    {{-- @if ($obj->canShowHistory())
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('admin.laporan.pembelian.history-pembelian-produk', [
                            'cabang_ids' => [$obj->cabang_id],
                            'produk_ids' => [$obj->id],
                        ]) }}"
                            target="_blank" class="dropdown-item">
                            <i class="ri-history-line label-icon align-middle fs-16 me-2"></i>
                            History Pembelian Produk
                        </a>
                        <a href="{{ route('admin.laporan.penjualan.history-penjualan-produk', [
                            'cabang_ids' => [$obj->cabang_id],
                            'produk_ids' => [$obj->id],
                        ]) }}"
                            target="_blank" class="dropdown-item">
                            <i class="ri-history-line label-icon align-middle fs-16 me-2"></i>
                            History Penjualan Produk
                        </a>
                    @endif --}}
                </x-slot>
                <x-slot name="body">
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
                                    <th>Kategori</th>
                                    <td>
                                        @if ($obj->kategoriProduk)
                                            <a href="{{ $obj->kategoriProduk->getRouteShow() }}">
                                                {{ $obj->kategoriProduk->nama }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Jenis</th>
                                    <td>
                                        @if ($obj->jenisProduk)
                                            <a href="{{ $obj->jenisProduk->getRouteShow() }}">
                                                {{ $obj->jenisProduk->nama }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Model</th>
                                    <td>
                                        @if ($obj->modelProduk)
                                            <a href="{{ $obj->modelProduk->getRouteShow() }}">
                                                {{ $obj->modelProduk->nama }}
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Satuan</th>
                                    <td>{{ $obj->satuan?->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Harga Beli</th>
                                    <td> {{ _number($obj->harga_beli) }}</td>
                                </tr>
                                <tr>
                                    <th>Harga Jual</th>
                                    <td> {{ _number($obj->harga_jual) }}</td>
                                </tr>
                                <tr>
                                    <th>Minimal Order</th>
                                    <td> {{ _number($obj->minimal_order) }} PCS</td>
                                </tr>
                                <tr>
                                    <th>Stok Minimum</th>
                                    <td> {{ _number($obj->stok_minimum) }} PCS</td>
                                </tr>
                                <tr>
                                    <th>Deskripsi</th>
                                    <td>{!! nl2br(e($obj->deskripsi)) !!}</td>
                                </tr>
                                <tr>
                                    <th>Internal Note</th>
                                    <td>{!! nl2br(e($obj->keterangan)) !!}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{{ $obj->status }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </x-slot>
            </x-admin::includes.pages.show-attributes-card>

            <div class="card"></div>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
