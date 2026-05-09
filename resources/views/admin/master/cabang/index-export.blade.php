<x-admin::layouts.export>
    <!-- TITLE -->
    <table class="mb-20">
        <tbody>
            <tr>
                <td>
                    <strong>MASTER CABANG</strong>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- PARAMETERS -->
    <table class="mb-20">
        <tbody>
            <tr>
                <td>Keywords : {{ $params['keyword'] ?? '-' }}</td>
            </tr>
            <tr>
                <td>Status : {{ $params['status'] ?? 'Semua Status' }}</td>
            </tr>
        </tbody>
    </table>

    <!-- DATA -->
    <table class="bordered">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="10%">Kode</th>
                <th width="15%">Nama</th>
                <th width="20%">Alamat</th>
                <th width="10%">Kota</th>
                <th width="10%">Telp</th>
                <th width="10%">Email</th>
                <th width="5%">PKP</th>
                <th width="5%">Include PPN</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $item)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $item->kode }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->alamat }}</td>
                    <td>{{ $item->kota }}</td>
                    <td>{{ $item->telp }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->is_pkp ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $item->is_include_ppn ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $item->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
