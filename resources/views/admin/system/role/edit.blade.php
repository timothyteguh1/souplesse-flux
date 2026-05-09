<div>
    @section('title', 'Ubah ' . $obj->name)

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item"><a href="{{ $obj->getRouteShow() }}">{{ $obj->name }}</a></li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <form wire:submit="submitDefault" x-data="{ permissionsTemp: @entangle('permissions') }">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <label class="col-lg-3 col-form-label">
                                Nama
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.text :name="'name'" placeholder="Masukkan nama" />
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-lg-3 col-form-label">
                                Status
                                <span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-9">
                                <x-admin::input.select2
                                    :name="'status'"
                                    :options="\App\Utilities\SelectHelpers\System\SH_Status::common()"
                                    :placeholder="'- Pilih Status -'"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                @if ($specialPermissions)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Special Permissions</h6>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr class="bg-light">
                                            <th colspan="2" class="text-end">Select/Unselect Section</th>
                                            <th class="text-center">
                                                <div class="form-check form-check-inline">
                                                    <input
                                                        class="form-check-input toggle-section"
                                                        type="checkbox"
                                                        data-section="specials"
                                                        style="width: 20px; height: 20px"
                                                    />
                                                </div>
                                            </th>
                                        </tr>

                                        <tr class="bg-light">
                                            <th class="text-center">#</th>
                                            <th>Permission</th>
                                            <th class="text-center">Enable</th>
                                        </tr>

                                        @foreach ($specialPermissions as $perm => $description)
                                            <tr data-section="specials">
                                                <td class="text-center">
                                                    {{ $loop->iteration }}
                                                </td>
                                                <td>{{ $description }}</td>
                                                <td class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            x-model="permissionsTemp"
                                                            style="width: 20px; height: 20px"
                                                            value="{{ $perm }}"
                                                        />
                                                    </div>
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
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        @foreach ($modulePermissions as $section => $perms)
                                            @if (! $loop->first)
                                                <tr>
                                                    <td colspan="{{ count($permissionActions) + 3 }}">&nbsp;</td>
                                                </tr>
                                            @endif

                                            <tr class="bg-light">
                                                <th colspan="{{ count($permissionActions) + 1 }}" class="text-end">
                                                    Select/Unselect Section
                                                </th>
                                                <th class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input
                                                            class="form-check-input toggle-section"
                                                            type="checkbox"
                                                            style="width: 20px; height: 20px"
                                                            data-section="{{ $section }}"
                                                        />
                                                    </div>
                                                </th>
                                                <th rowspan="3"></th>
                                            </tr>

                                            <tr class="bg-light">
                                                <th colspan="2">{{ $section }}</th>
                                                @foreach ($permissionActions as $act)
                                                    <th class="text-center">
                                                        <div class="form-check form-check-inline">
                                                            <input
                                                                class="form-check-input toggle-column"
                                                                type="checkbox"
                                                                style="width: 20px; height: 20px"
                                                                data-column="{{ \Illuminate\Support\Str::slug($act) }}"
                                                                data-section="{{ $section }}"
                                                            />
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>

                                            <tr class="bg-light">
                                                <th class="text-center">#</th>
                                                <th>Module</th>
                                                @foreach ($permissionActions as $act)
                                                    <th class="text-center">
                                                        {{ ucwords($act) }}
                                                    </th>
                                                @endforeach
                                            </tr>

                                            @foreach ($perms as $index => $perm)
                                                <tr data-section="{{ $section }}">
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>{{ $perm }}</td>
                                                    @foreach ($permissionActions as $key => $act)
                                                        <td class="text-center">
                                                            <div class="form-check form-check-inline">
                                                                <input
                                                                    class="form-check-input {{ \Illuminate\Support\Str::slug($act) }}"
                                                                    type="checkbox"
                                                                    style="width: 20px; height: 20px"
                                                                    x-model="permissionsTemp"
                                                                    value="{{ $index }}.{{ $key }}"
                                                                />
                                                            </div>
                                                        </td>
                                                    @endforeach

                                                    <td class="text-center">
                                                        <div class="form-check form-check-inline">
                                                            <input
                                                                class="form-check-input toggle-row"
                                                                type="checkbox"
                                                                style="width: 20px; height: 20px"
                                                            />
                                                        </div>
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

                @if ($reportPermissions)
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Report Permissions</h6>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        @foreach ($reportPermissions as $section => $perms)
                                            @if (! $loop->first)
                                                <tr>
                                                    <td colspan="3">&nbsp;</td>
                                                </tr>
                                            @endif

                                            <tr class="bg-light">
                                                <th colspan="2">{{ $section }}</th>
                                                <th class="text-center">
                                                    <div class="form-check form-check-inline">
                                                        <input
                                                            class="form-check-input toggle-section"
                                                            type="checkbox"
                                                            style="width: 20px; height: 20px"
                                                            data-section="{{ $section }}"
                                                        />
                                                    </div>
                                                </th>
                                            </tr>

                                            <tr class="bg-light">
                                                <th class="text-center">#</th>
                                                <th>Module</th>
                                                <th class="text-center">Enable</th>
                                            </tr>

                                            @foreach ($perms as $index => $perm)
                                                <tr data-section="{{ $section }}">
                                                    <td class="text-center">
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>{{ $perm }}</td>
                                                    <td class="text-center">
                                                        <div class="form-check form-check-inline">
                                                            <input
                                                                class="form-check-input"
                                                                type="checkbox"
                                                                style="width: 20px; height: 20px"
                                                                x-model="permissionsTemp"
                                                                value="{{ $index }}"
                                                            />
                                                        </div>
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

                <div class="card">
                    <x-admin::includes.pages.edit-footer-action />
                </div>
            </form>
        </div>
    </div>
    <!--end row-->

    @push('after-scripts')
        <script>
            $(function () {
                $('.toggle-section').change(function () {
                    var $this = $(this);
                    var section = $this.attr('data-section');
                    var tr = $('tr[data-section="' + section + '"]');
                    if (this.checked) {
                        tr.find('input[type="checkbox"]:not(:checked)').click();
                    } else {
                        tr.find('input[type="checkbox"]:checked').click();
                    }
                });

                $('.toggle-column').change(function () {
                    var $this = $(this);
                    var section = $this.attr('data-section');
                    var column = $this.attr('data-column');
                    var tr = $('tr[data-section="' + section + '"]');
                    if (this.checked) {
                        tr.find('input[type="checkbox"].' + column + ':not(:checked)').click();
                    } else {
                        tr.find('input[type="checkbox"].' + column + ':checked').click();
                    }
                });

                $('.toggle-row').change(function () {
                    var $this = $(this);
                    var $tr = $this.closest('tr');
                    if (this.checked) {
                        $tr.find('input[type="checkbox"]:not(:checked)').click();
                    } else {
                        $tr.find('input[type="checkbox"]:checked').click();
                    }
                });
            });
        </script>
    @endpush
</div>
