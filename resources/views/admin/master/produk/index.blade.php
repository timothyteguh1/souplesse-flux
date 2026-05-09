<div>
    @section('title', $menuTitle)

    @section('breadcrumb')
        <li class="breadcrumb-item active">Daftar {{ $menuTitle }}</li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <x-admin::includes.pages.browse-header :model="$model" :import-button="true" />

                <x-admin::includes.pages.browse-filter>
                    <div class="row g-3">
                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.search-box :name="'keyword'" placeholder="Kode / Nama / Internal Note" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :name="'jenis_produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_JenisProduk::active()" :placeholder="'- Semua Jenis Produk -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :name="'kategori_produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_KategoriProduk::active()" :placeholder="'- Semua Kategori Produk -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :name="'model_produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_ModelProduk::active()" :placeholder="'- Semua Model Produk -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2 :name="'status'" :options="\App\Utilities\SelectHelpers\System\SH_Status::common()" :placeholder="'- Semua Status -'" />
                        </div>
                        <!--end col-->

                        <div class="col-12">
                            <x-admin::buttons.browse-filter />
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </x-admin::includes.pages.browse-filter>

                <x-admin::includes.pages.browse-table :data="$data">
                    <x-slot name="head">
                        <tr>
                            <th class="text-uppercase" style="width: 45px">No</th>
                            <x-admin::utils.th-sortable-cabang :label="'Cabang'" :field="'cabang_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Kode'" :field="'kode'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Nama'" :field="'nama'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Kategori'" :field="'kategori_produk_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Jenis'" :field="'jenis_produk_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Model'" :field="'model_produk_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <th class="text-uppercase text-end">Harga Beli</th>
                            <th class="text-uppercase text-end">Harga Jual</th>
                            <th class="text-uppercase text-end">Min Order</th>
                            <x-admin::utils.th-sortable :label="'Internal Note'" :field="'keterangan'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Status'" :field="'status'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" style="width: 150px" />
                            <th class="text-uppercase text-center" style="width: 80px">Action</th>
                        </tr>
                    </x-slot>

                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $no_item++ }}</td>
                            <x-admin::includes.pages.browse-table-td-cabang :obj="$obj" />
                            <td><a href="{{ $obj->getRouteShow() }}">{{ $obj->kode }}</a></td>
                            <td>{{ $obj->nama }}</td>
                            <td>
                                @if ($obj->kategoriProduk)
                                    <a href="{{ $obj->kategoriProduk->getRouteShow() }}">
                                        {{ $obj->kategoriProduk->nama }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($obj->jenisProduk)
                                    <a href="{{ $obj->jenisProduk->getRouteShow() }}">
                                        {{ $obj->jenisProduk->nama }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($obj->modelProduk)
                                    <a href="{{ $obj->modelProduk->getRouteShow() }}">
                                        {{ $obj->modelProduk->nama }}
                                    </a>
                                @endif
                            </td>
                            <td class="text-end">{{ _number($obj->harga_beli) }}</td>
                            <td class="text-end">{{ _number($obj->harga_jual) }}</td>
                            <td class="text-end">{{ _number($obj->minimal_order) }}</td>
                            <td>{{ $obj->keterangan }}</td>
                            <td>{{ $obj->status }}</td>
                            <td class="text-center">
                                <x-admin::includes.pages.browse-table-action :obj="$obj">
                                    {{-- @if ($obj->canShowHistory())
                                        <li>
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
                                        </li>
                                    @endif --}}
                                </x-admin::includes.pages.browse-table-action>
                            </td>
                        </tr>
                    @endforeach
                </x-admin::includes.pages.browse-table>
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
    <!--end row-->
</div>
