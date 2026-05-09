<div>
    @section('title', $menuTitle)

    @section('breadcrumb')
        <li class="breadcrumb-item active">{{ $menuTitle }} List</li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <x-admin::includes.pages.browse-header :model="$model" :create-button="false" />

                <x-admin::includes.pages.browse-filter>
                    <div class="row g-3">
                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.date-time-range :name="'tanggal'" :placeholder="'- Semua Tanggal -'" />
                        </div>

                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.select2 :name="'gudang_id'" :options="\App\Utilities\SelectHelpers\Master\SH_Gudang::active()" :placeholder="'- Semua Gudang -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.select2 :name="'produk_id'" :options="\App\Utilities\SelectHelpers\Master\SH_Produk::active()" :placeholder="'- Semua Produk -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.select2 :name="'satuan_transaksi_id'" :options="\App\Utilities\SelectHelpers\Master\SH_Satuan::active()" :placeholder="'- Semua Satuan -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.select2 :name="'jenis_transaksi'" :options="\App\Utilities\SelectHelpers\System\SH_MutasiStok::jenisTransaksi()" :placeholder="'- Semua Jenis Transaksi -'" />
                        </div>
                        <!--end col-->

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
                            <x-admin::utils.th-sortable :label="'Jenis Transaksi'" :field="'jenis_transaksi'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Gudang'" :field="'gudang_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Produk'" :field="'produk_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Satuan Transaksi'" :field="'satuan_transaksi_id'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <th class="text-uppercase">Reference</th>
                            <x-admin::utils.th-sortable :label="'ED'" :field="'expired_date'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'No Batch'" :field="'no_batch'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Jumlah Transaksi'" :field="'jumlah_transaksi'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" :align="'end'" />
                            <x-admin::utils.th-sortable :label="'Harga Transaksi'" :field="'harga_transaksi'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" :align="'end'" />
                            <th class="text-uppercase text-center" style="width: 80px">Action</th>
                        </tr>
                    </x-slot>

                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $no_item++ }}</td>
                            <x-admin::includes.pages.browse-table-td-cabang :obj="$obj" />
                            <td>{{ $obj->jenis_transaksi }}</td>
                            <td>
                                <a href="{{ $obj->gudang?->getRouteShow() }}">
                                    {{ $obj->gudang?->nama }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $obj->produk?->getRouteShow() }}">
                                    {{ $obj->produk?->nama }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $obj->satuanTransaksi?->getRouteShow() }}">
                                    {{ $obj->satuanTransaksi?->nama }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $obj->header?->getRouteShow() }}">
                                    {{ $obj->header?->kode }}
                                </a>
                            </td>
                            <td>{{ $obj->expired_date }}</td>
                            <td>{{ $obj->no_batch }}</td>
                            <td class="text-end">{{ _number($obj->jumlah_transaksi) }}</td>
                            <td class="text-end">{{ _number($obj->harga_transaksi) }}</td>
                            <td class="text-center">
                                @can($obj->getPermissionShow())
                                    <a class="btn btn-soft-secondary btn-sm" href="{{ $obj->getRouteShow() }}">
                                        <i class="ri-eye-fill align-bottom"></i>
                                    </a>
                                @endcan
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
