<div>
    @section('title', $menuTitle)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $model::routeIndex() }}">{{ $menuTitle }}</a></li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form wire:submit="submitDefault">
                <div class="card" x-data="{ activeTab: 'umum' }">
                    <div class="card-body">
                        <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link" :class="activeTab == 'umum' && 'active'" data-bs-toggle="tab"
                                    href="#tabUmum" role="tab" @click="activeTab = 'umum'">
                                    Data Umum
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a
                                    class="nav-link"
                                    :class="activeTab == 'cabang' && 'active'"
                                    data-bs-toggle="tab"
                                    href="#tabCabang"
                                    role="tab"
                                    @click="activeTab = 'cabang'"
                                >
                                    Cabang
                                </a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" :class="activeTab == 'kas' && 'active'" data-bs-toggle="tab"
                                    href="#tabKas" role="tab" @click="activeTab = 'kas'">
                                    Kas
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="activeTab == 'gudang' && 'active'" data-bs-toggle="tab"
                                    href="#tabGudang" role="tab" @click="activeTab = 'gudang'">
                                    Gudang
                                </a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-body p-4">
                        <div class="tab-content">
                            <div class="tab-pane" :class="activeTab == 'umum' && 'active'" id="tabUmum"
                                role="tabpanel">
                                <div class="row">
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">
                                            Nama
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'name'" placeholder="Masukkan nama" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">E-Mail</label>
                                        <div class="col-lg-9">
                                            <x-admin::input.email :name="'email'" placeholder="Masukkan email" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">
                                            Username
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'username'" placeholder="Masukkan username" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">
                                            Password
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <x-admin::input.text :name="'password'" placeholder="Masukkan password" />
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-lg-3 col-form-label">
                                            Roles
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-9">
                                            <x-admin::input.tags :name="'role_ids'" :options="\App\Utilities\SelectHelpers\System\SH_Role::active()"
                                                :placeholder="'- Pilih Roles -'" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" :class="activeTab == 'cabang' && 'active show'" id="tabCabang"
                                role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-8 col-lg-6">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered table-striped align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-uppercase text-center">Cabang</th>
                                                        <th class="text-uppercase text-center" style="width: 50px">
                                                            <x-admin::input.checkbox :name="'isCheckedAllCabang'" :value="!$isCheckedAllCabang"
                                                                :form-check-class="false" :wire:key="'toggleCheckAllCabang'"
                                                                :wire:click="'toggleCheckAllCabang'" />
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($cabangs as $index => $item)
                                                        <tr>
                                                            <td>{{ $item['nama'] }}</td>
                                                            <td class="text-center">
                                                                <x-admin::input.checkbox :name="'cabang_ids.' . $item['id']"
                                                                    :value="$item['id']" :form-check-class="false"
                                                                    :defer="false"
                                                                    :wire:key="'cabang_ids_' . $loop->index" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" :class="activeTab == 'kas' && 'active show'" id="tabKas"
                                role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-8 col-lg-6">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered table-striped align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-uppercase text-center">Kas</th>
                                                        <th class="text-uppercase text-center" style="width: 50px">
                                                            <x-admin::input.checkbox :name="'isCheckedAllKas'"
                                                                :value="!$isCheckedAllKas" :form-check-class="false"
                                                                :wire:key="'toggleCheckAllKas'"
                                                                :wire:click="'toggleCheckAllKas'" />
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($kas as $index => $item)
                                                        <tr>
                                                            <td>{{ $item['nama'] }}</td>
                                                            <td class="text-center">
                                                                <x-admin::input.checkbox :name="'kas_ids.' . $item['id']"
                                                                    :value="$item['id']" :form-check-class="false"
                                                                    :wire:key="'kas_ids_' . $loop->index" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" :class="activeTab == 'gudang' && 'active show'" id="tabGudang"
                                role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-8 col-lg-6">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered table-striped align-middle table-nowrap mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-uppercase text-center">Gudang</th>
                                                        <th class="text-uppercase text-center" style="width: 50px">
                                                            <x-admin::input.checkbox :name="'isCheckedAllGudang'"
                                                                :value="!$isCheckedAllGudang" :form-check-class="false"
                                                                :wire:key="'toggleCheckAllGudang'"
                                                                :wire:click="'toggleCheckAllGudang'" />
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($gudangs as $index => $item)
                                                        <tr>
                                                            <td>{{ $item['nama'] }}</td>
                                                            <td class="text-center">
                                                                <x-admin::input.checkbox :name="'gudang_ids.' . $item['id']"
                                                                    :value="$item['id']" :form-check-class="false"
                                                                    :wire:key="'gudang_ids_' . $loop->index" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end tab-pane-->
                        </div>
                    </div>

                    <x-admin::includes.pages.create-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
</div>
