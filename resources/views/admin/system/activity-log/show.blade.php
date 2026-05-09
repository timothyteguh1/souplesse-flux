<div>
    @section('title', "Detail {$menuTitle}")

    @section('breadcrumb')
        <li class="breadcrumb-item"><a href="{{ $model::routeIndex() }}">{{ $menuTitle }}</a></li>
        <li class="breadcrumb-item active">Detail {{ $menuTitle }}</li>
    @endsection

    <div class="row">
        <div class="col-12">
            <x-admin::includes.alert-messages />
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <x-admin::includes.pages.show-attributes-card :show-action-buttons="false">
                <tr>
                    <th width="20%">Date Time</th>
                    <td>
                        {{ $obj->created_at }}
                    </td>
                </tr>
                <tr>
                    <th>User</th>
                    <td>
                        @php
                            $causer = $obj->causer;
                        @endphp

                        @if ($causer)
                            <a href="{{ $causer->getRouteShow() }}">
                                {{ optional($causer)->name }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Reference</th>
                    <td>
                        @php
                            $subject = $obj->subject;
                        @endphp

                        @if ($subject)
                            <a href="{{ $subject->getRouteShow() }}">
                                {{ $subject->name ?? ($subject->nama ?? $subject->kode) }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ $obj->description }}</td>
                </tr>
                <tr>
                    <th>Event</th>
                    <td>{{ $obj->event }}</td>
                </tr>
            </x-admin::includes.pages.show-attributes-card>
        </div>
        <!--end col-->
    </div>
    <!--end row-->

    <div class="row">
        @if (filled($new))
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-success">
                        <h6 class="card-title mb-0">New Attributes</h6>
                    </div>
                    <div class="card-body">
                        <pre><code>{{ json_encode($new, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        @endif

        @if (filled($old))
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-warning">
                        <h6 class="card-title mb-0">Old Attributes</h6>
                    </div>
                    <div class="card-body">
                        <pre><code>{{ json_encode($old, JSON_PRETTY_PRINT) }}</code></pre>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
