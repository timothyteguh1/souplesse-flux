<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th>Date Time</th>
                <th>User</th>
                <th>Reference</th>
                <th>Description</th>
                <th>Event</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->created_at }}</td>
                    <td>
                        @php
                            $causer = $obj->causer;
                        @endphp

                        @if ($causer)
                            {{ optional($causer)->name }}
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
                                {{ $subject->name ?? ($subject->nama ?? $subject->kode) }}
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
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
