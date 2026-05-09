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
                        <div class="col-xxl-6 col-sm-6">
                            <x-admin::input.search-box :name="'keyword'"
                                placeholder="Kode / Nama / Alamat / Telp / Keterangan" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.select2 :name="'status'" :options="\App\Utilities\SelectHelpers\System\SH_Status::common()" :placeholder="'- Semua Status -'" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-2 col-sm-12">
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
                            <x-admin::utils.th-sortable :label="'Nama'" :field="'nama'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Alamat'" :field="'alamat'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Kota'" :field="'kota'" :sort-field="$sortField"
                                :sort-asc="$sortAsc" />
                            <x-admin::utils.th-sortable :label="'Telp Bisnis'" :field="'telp'" :sort-field="$sortField"
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
                            <td>
                                <a href="{{ $obj->getRouteShow() }}">{{ $obj->kode }} - {{ $obj->nama }}</a>
                            </td>
                            <td>{{ $obj->alamat }}</td>
                            <td>{{ $obj->kota }}</td>
                            <td>{{ $obj->telp }}</td>
                            <td>{{ $obj->status }}</td>
                            <td class="text-center">
                                <x-admin::includes.pages.browse-table-action :obj="$obj">
                                    {{-- @if ($obj->canShowHistory())
                                        <li>
                                            <a href="{{ route('admin.laporan.pembelian.history-pembelian-produk', [
                                                'cabang_ids' => [$obj->cabang_id],
                                                'supplier_ids' => [$obj->id],
                                            ]) }}"
                                                target="_blank" class="dropdown-item">
                                                <i class="ri-history-line label-icon align-middle fs-16 me-2"></i>
                                                History Pembelian Produk
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
