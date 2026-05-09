@props(['showNpwp' => false])

@php($perusahaan = \App\Models\Master\Perusahaan::find(\App\Utilities\Constants\Const_Perusahaan::PT))

<table class="table-spaced">
    <tbody>
        <tr>
            <td><strong>{{ $perusahaan->nama }}</strong></td>
        </tr>
        <tr>
            <td>{{ $perusahaan->alamat }}</td>
        </tr>
        <tr>
            <td>{{ $perusahaan->kota }}</td>
        </tr>
        @if ($showNpwp)
            <tr>
                <td>NPWP {{ $perusahaan->npwp_kode }}</td>
            </tr>
        @endif
    </tbody>
</table>
