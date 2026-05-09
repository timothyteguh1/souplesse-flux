<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->name }}</td>
                    <td>{{ $obj->username }}</td>
                    <td>{{ $obj->email }}</td>
                    <td>
                        @forelse ($obj->roles()->get() as $role)
                            {{ $role->name }}
                        @empty
                            Tidak ada
                        @endforelse
                    </td>
                    <td>{{ $obj->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
