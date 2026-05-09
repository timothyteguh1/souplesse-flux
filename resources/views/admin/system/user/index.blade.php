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
                        <div class="col-xxl-4 col-sm-12">
                            <x-admin::input.search-box :name="'keyword'" placeholder="Kode / Keterangan" />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-4 col-sm-12">
                            <x-admin::input.select2
                                :name="'role_id'"
                                :options="\App\Utilities\SelectHelpers\System\SH_Role::active()"
                                :placeholder="'- Semua Role -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-4 col-sm-6">
                            <x-admin::input.select2
                                :name="'status'"
                                :options="\App\Utilities\SelectHelpers\System\SH_Status::common()"
                                :placeholder="'- Semua Status -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-12 col-sm-6">
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
                            <x-admin::utils.th-sortable
                                :label="'Nama'"
                                :field="'name'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Username'"
                                :field="'username'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Email'"
                                :field="'email'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <th>Role</th>
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
                            <td><a href="{{ $obj->getRouteShow() }}">{{ $obj->name }}</a></td>
                            <td>{{ $obj->username }}</td>
                            <td>{{ $obj->email }}</td>
                            <td>
                                @forelse ($obj->roles()->get() as $role)
                                    <a href="{{ $role->getRouteShow() }}">
                                        <span class="badge bg-success">
                                            {{ $role->name }}
                                        </span>
                                    </a>
                                @empty
                                    <span class="badge bg-danger">Tidak ada</span>
                                @endforelse
                            </td>
                            <td>{{ $obj->status }}</td>
                            <td class="text-center">
                                <x-admin::includes.pages.browse-table-action :obj="$obj">
                                    @canImpersonate()
                                    @canBeImpersonated($obj)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('admin.impersonate', $obj->id) }}">
                                            <i class="ri-shield-user-fill align-bottom me-2 text-muted"></i>
                                            Impersonate
                                        </a>
                                    </li>
                                    @endCanBeImpersonated
                                    @endCanImpersonate
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
