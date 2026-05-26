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
                <x-admin::includes.pages.browse-header :create-button="false"></x-admin::includes.pages.browse-header>

                <x-admin::includes.pages.browse-filter>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <x-admin::input.search-box :name="'keyword'" placeholder="Kode / Nama" />
                        </div>
                        <!--end col-->

                        <div class="col-sm-6">
                            <x-admin::input.select2 :name="'kategori_id'" :options="\App\Utilities\SelectHelpers\Master\SH_KategoriProduk::active()" :placeholder="'- Semua Kategori Produk -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-12 col-sm-12">
                            <x-admin::input.checkbox :name="'isDibawahStokMinimum'" :label="'Dibawah Stok Minimum'" :value="$isDibawahStokMinimum"
                                :inline="true" />
                        </div>

                        <div class="col-xxl-12 col-sm-12">
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
                            <x-admin::utils.th-sortable :label="'Kategori'" :field="'produks.kategori_produk_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <th class="text-uppercase text-end">Jml Satuan Dasar</th>
                            <x-admin::utils.th-sortable :label="'Stok Minimum'" :field="'stok_minimum'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                        </tr>
                    </x-slot>
                    @foreach ($data->loadMissing(['gudang', 'header', 'produk.kategoriProduk', 'satuan']) as $obj)
                        <tr @if ($obj->total < $obj->stok_minimum) class="bg-danger" @endif>
                            <td>{{ $no_item++ }}</td>
                            <x-admin::includes.pages.browse-table-td-cabang :obj="$obj" />
                            <td><a href="{{ $obj->produk->getRouteShow() }}">{{ $obj->produk->kode }}</a></td>
                            <td>{{ $obj->produk->nama }}</td>
                            <td>
                                <a href="{{ optional($obj->produk->kategoriProduk)->getRouteShow() }}">
                                    {{ optional($obj->produk->kategoriProduk)->nama }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{ _number($obj->total) }}
                                {{ $obj->satuan->nama }}
                            </td>
                            <td class="text-end">
                                {{ _number($obj->stok_minimum) }}
                                {{ $obj->satuan->nama }}
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
