@props([
    'obj',
])

<div class="card">
    <div class="card-header bg-info-subtle">
        <h6 class="card-title mb-0">Activity History</h6>
    </div>

    <div class="card-body">
        @php
            $activities = \App\Models\Activity::query()
                ->with('causer')
                ->forSubject($obj)
                ->latest()
                ->get();
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Date Time</th>
                        <th>User</th>
                        <th>Event</th>
                        @can(\App\Models\Activity::permissionShow())
                            <th class="text-center" width="10%">Detail</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at }}</td>
                            <td>
                                {{ optional(optional($activity)->causer)->name ?? '-' }}
                            </td>
                            <td>{{ $activity->event }}</td>
                            @can(\App\Models\Activity::permissionShow())
                                <td>
                                    <a href="{{ $activity->getRouteShow() }}" class="btn btn-sm btn-soft-info w-100">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
