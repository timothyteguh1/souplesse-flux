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
                <x-admin::includes.pages.browse-header :model="$model" />

                <x-admin::includes.pages.browse-filter>
                    <div class="row g-3">
                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.date-range :name="'tanggal'" :placeholder="'- Semua Tanggal -'" />
                        </div>

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.search-box :name="'keyword'" placeholder="Kode / Keterangan" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2
                                :name="'customer_id'"
                                :options="\App\Utilities\SelectHelpers\Master\SH_Customer::active()"
                                :placeholder="'- Semua Customer -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2
                                :name="'gudang_id'"
                                :options="\App\Utilities\SelectHelpers\Master\SH_Gudang::user()"
                                :placeholder="'- Semua Gudang -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2
                                :name="'status'"
                                :options="\App\Utilities\SelectHelpers\System\SH_Status::faktur_penjualan()"
                                :placeholder="'- Semua Status -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-12">
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
                            <x-admin::utils.th-sortable-cabang
                                :label="'Cabang'"
                                :field="'cabang_id'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Kode'"
                                :field="'kode'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Tanggal'"
                                :field="'tanggal'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Customer'"
                                :field="'customer_id'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Gudang'"
                                :field="'gudang_id'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <th class="text-uppercase text-end">Grand Total</th>
                            <x-admin::utils.th-sortable
                                :label="'Status'"
                                :field="'status'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                                style="width: 150px"
                            />
                            <th class="text-uppercase text-center" style="width: 80px">Action</th>
                        </tr>
                    </x-slot>

                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $no_item++ }}</td>
                            <x-admin::includes.pages.browse-table-td-cabang :obj="$obj" />
                            <td>
                                <a href="{{ $obj->getRouteShow() }}">
                                    {{ $obj->kode }}
                                </a>
                            </td>
                            <td>{{ _date_format_output($obj->tanggal) }}</td>
                            <td>
                                <a href="{{ $obj->customer->getRouteShow() }}">
                                    {{ $obj->customer->nama }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ $obj->gudang->getRouteShow() }}">
                                    {{ $obj->gudang->nama }}
                                </a>
                            </td>
                            <td class="text-end">
                                {{ _number($obj->grandtotal) }}
                            </td>
                            <td>{{ $obj->status }}</td>
                            <td class="text-center">
                                <x-admin::includes.pages.browse-table-action :obj="$obj">
                                    @if ($obj->canPrint())
                                        <li>
                                            <button class="dropdown-item" wire:click="print('{{ $obj->id }}')">
                                                <i class="ri-printer-line align-bottom me-2 text-muted"></i>
                                                Print
                                            </button>
                                        </li>
                                    @endif
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
