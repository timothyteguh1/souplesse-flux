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

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.select2
                                :name="'user_id'"
                                :options="\App\Utilities\SelectHelpers\System\SH_User::active()"
                                :placeholder="'- Semua User -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-2 col-sm-6">
                            <x-admin::input.select2
                                :name="'event'"
                                :options="\App\Utilities\SelectHelpers\System\SH_ActivityLog::events()"
                                :placeholder="'- Semua Event -'"
                            />
                        </div>
                        <!--end col-->

                        <div class="col-xxl-3 col-sm-6">
                            <x-admin::input.search-box :name="'description'" placeholder="Keterangan" />
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
                            <x-admin::utils.th-sortable
                                :label="'Date Time'"
                                :field="'created_at'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <th class="text-uppercase">User</th>
                            <th class="text-uppercase">Reference</th>
                            <x-admin::utils.th-sortable
                                :label="'Description'"
                                :field="'description'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <x-admin::utils.th-sortable
                                :label="'Event'"
                                :field="'event'"
                                :sort-field="$sortField"
                                :sort-asc="$sortAsc"
                            />
                            <th class="text-uppercase text-center" style="width: 80px">Action</th>
                        </tr>
                    </x-slot>

                    @foreach ($data as $obj)
                        <tr>
                            <td>{{ $no_item++ }}</td>
                            <td>{{ $obj->created_at }}</td>
                            <td>
                                @php
                                    $causer = $obj->causer;
                                @endphp

                                @if ($causer)
                                    <a href="{{ $causer->getRouteShow() }}">
                                        {{ optional($causer)->name }}
                                    </a>
                                @else
                                    Deleted:
                                    <br />
                                    {{ $obj->causer_id }}
                                @endif
                            </td>
                            <td>
                                @php
                                    $subject = $obj->subject;
                                @endphp

                                @if ($subject)
                                    @if (method_exists($subject, 'getRouteShow'))
                                        <a href="{{ $subject->getRouteShow() }}">
                                            {{ $subject->name ?? ($subject->nama ?? $subject->kode) }}
                                        </a>
                                    @else
                                        {{ $subject->name ?? ($subject->nama ?? $subject->kode) }}
                                    @endif
                                @elseif ($obj->event == 'deleted')
                                    {{ $obj->properties['old']['name'] ?? ($obj->properties['old']['nama'] ?? ($obj->properties['old']['kode'] ?? '')) }}
                                @else
                                    {{ $obj->properties['attributes']['name'] ?? ($obj->properties['attributes']['nama'] ?? ($obj->properties['attributes']['kode'] ?? '')) }}
                                @endif
                            </td>
                            <td>{{ $obj->description }}</td>
                            <td>{{ $obj->event }}</td>
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
