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
                <x-admin::includes.pages.browse-filter>
                    <div class="row g-3">
                        <div class="col-xxl-2 col-sm-6">
                            <x-admin::input.select2
                                :name="'disk_id'"
                                :options="$disks"
                                :placeholder="'- Semua Disk -'"
                            />
                        </div>

                        <div class="col-xxl-8 col-sm-6">
                            <x-admin::input.date-range :name="'tanggal'" :placeholder="'- Semua Tanggal -'" />
                        </div>

                        <div class="col-xxl-2 col-sm-12">
                            <x-admin::buttons.browse-filter />
                        </div>
                        <!--end col-->
                    </div>
                    <!--end row-->
                </x-admin::includes.pages.browse-filter>

                <x-admin::includes.pages.browse-table :data="$data" :pagination="false">
                    <x-slot name="head">
                        <tr>
                            <th class="text-uppercase" style="width: 45px">No</th>
                            <th class="text-uppercase">Disc</th>
                            <th class="text-uppercase">Date Time</th>
                            <th class="text-uppercase">File</th>
                            <th class="text-uppercase text-center" style="width: 80px">Action</th>
                        </tr>
                    </x-slot>

                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $no_item++ }}</td>
                            <td>{{ $obj['disk'] }}</td>
                            <td>{{ $obj['date'] }}</td>
                            <td>{{ $obj['path'] }}</td>
                            <td class="text-center">
                                <x-admin::includes.pages.browse-table-action
                                    :view-button="false"
                                    :edit-button="false"
                                    :delete-button="false"
                                >
                                    <li>
                                        <span
                                            role="button"
                                            class="dropdown-item"
                                            wire:click="processRestore('{{ $obj['disk'] }}', '{{ $obj['path'] }}')"
                                        >
                                            <i class="ri-upload-2-fill align-bottom me-2 text-muted"></i>
                                            Restore
                                        </span>
                                    </li>
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
