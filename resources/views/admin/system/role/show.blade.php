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
                <tr>
                    <th width="20%">Nama</th>
                    <td width="80%">{{ $obj->name }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $obj->status }}</td>
                </tr>
            </x-admin::includes.pages.show-attributes-card>

            @if ($specialPermissions)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Special Permissions</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="bg-light">
                                        <th class="text-center">#</th>
                                        <th>Modul</th>
                                        <th class="text-center">Enabled</th>
                                    </tr>

                                    @foreach ($specialPermissions as $perm => $desc)
                                        <tr>
                                            <td class="text-center">
                                                {{ $loop->iteration }}
                                            </td>
                                            <td>{{ $desc }}</td>
                                            <td>
                                                @if (in_array($perm, $permissions))
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
                </div>
            @endif

            @if ($modulePermissions)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Module Permissions</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($modulePermissions as $section => $perms)
                                        <tr class="bg-light">
                                            <th colspan="{{ count($permissionActions) + 2 }}">
                                                {{ $section }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Module</th>
                                            @foreach ($permissionActions as $act)
                                                <th class="text-center">
                                                    {{ ucwords($act) }}
                                                </th>
                                            @endforeach
                                        </tr>

                                        @foreach ($perms as $index => $perm)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>{{ $perm }}</td>
                                                @foreach ($permissionActions as $key => $act)
                                                    <td>
                                                        @if (in_array($index . '.' . $key, $permissions))
                                                            <div class="text-center">
                                                                <span class="ri-check-fill text-success"></span>
                                                            </div>
                                                        @else
                                                            <div class="text-center">
                                                                <span class="ri-close-fill text-danger"></span>
                                                            </div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if ($reportPermissions)
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Report Permissions</h6>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    @foreach ($reportPermissions as $section => $perms)
                                        <tr class="bg-light">
                                            <th colspan="7">
                                                {{ $section }}
                                            </th>
                                        </tr>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>Module</th>
                                            <th class="text-center">Enable</th>
                                        </tr>

                                        @foreach ($perms as $index => $perm)
                                            <tr>
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>{{ $perm }}</td>
                                                <td>
                                                    @if (in_array($index, $permissions))
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <x-admin::includes.pages.show-footer-card :obj="$obj" />
</div>
