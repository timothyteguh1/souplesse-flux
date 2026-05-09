<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Telp</th>
                <th>Email</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->kode }} - {{ $obj->nama }}</td>
                    <td>{{ $obj->alamat }}</td>
                    <td>{{ $obj->kota }}</td>
                    <td>{{ $obj->telp }}</td>
                    <td>{{ $obj->email }}</td>
                    <td>{{ $obj->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
