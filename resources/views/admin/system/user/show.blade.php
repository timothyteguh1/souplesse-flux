<div>
    @section('title', $obj->name)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item active">{{ $obj->name }}</li>
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
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('admin.impersonate', $obj->id) }}">
                        <i class="ri-shield-user-fill label-icon align-middle fs-16 me-2"></i>
                        Impersonate
                    </a>
                </x-slot>

                <x-slot name="body">
                    <div>
                        <ul class="nav nav-custom-light nav-border-top nav-border-top-primary nav-justified rounded card-header-tabs border"
                            role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#dataUmum" role="tab">
                                    Data Umum
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabCabang" role="tab">Cabang</a>
                            </li> --}}
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabKas" role="tab">Kas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#tabGudang" role="tab">Gudang</a>
                            </li>
                        </ul>
                    </div>

                    <div class="pt-4">
                        <div class="tab-content">
                            <div class="tab-pane active" id="dataUmum" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th width="20%">Roles</th>
                                                <td>
                                                    @forelse ($obj->getRoleNames() as $role)
                                                        <span class="badge bg-success">{{ $role }}</span>
                                                    @empty
                                                        <span class="badge bg-danger">Tidak ada</span>
                                                    @endforelse
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Nama</th>
                                                <td>{{ $obj->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Username</th>
                                                <td>{{ $obj->username }}</td>
                                            </tr>
                                            <tr>
                                                <th>E-mail Address</th>
                                                <td>{{ $obj->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>{{ $obj->status }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="tabCabang" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th class="text-center">#</th>
                                                <th>Cabang</th>
                                                <th class="text-center">Enabled</th>
                                            </tr>

                                            @foreach ($cabangs as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $item->nama }}
                                                    </td>
                                                    <td>
                                                        @if (in_array($item->id, $selectedCabangIds))
                                                            <div class="text-center">
                                                                <span class="ri-check-fill text-success"></span>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <span class="ri-close-fill text-danger"></span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="tabKas" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th class="text-center">#</th>
                                                <th>Kas</th>
                                                <th class="text-center">Enabled</th>
                                            </tr>

                                            @foreach ($kas as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $item->nama }}
                                                    </td>
                                                    <td>
                                                        @if (in_array($item->id, $selectedKasIds))
                                                            <div class="text-center">
                                                                <span class="ri-check-fill text-success"></span>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <span class="ri-close-fill text-danger"></span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end tab-pane-->
                            <div class="tab-pane" id="tabGudang" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr class="bg-light">
                                                <th class="text-center">#</th>
                                                <th>Gudang</th>
                                                <th class="text-center">Enabled</th>
                                            </tr>

                                            @foreach ($gudangs as $item)
                                                <tr>
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        {{ $item->nama }}
                                                    </td>
                                                    <td>
                                                        @if (in_array($item->id, $selectedGudangIds))
                                                            <div class="text-center">
                                                                <span class="ri-check-fill text-success"></span>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <span class="ri-close-fill text-danger"></span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!--end tab-pane-->
                        </div>
                    </div>
                </x-slot>
            </x-admin::includes.pages.show-attributes-card>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
