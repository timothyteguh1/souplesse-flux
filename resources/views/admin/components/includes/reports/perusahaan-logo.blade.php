@php($perusahaan = \App\Models\Master\Perusahaan::find(\App\Utilities\Constants\Const_Perusahaan::PT))

<table class="valign-top">
    <tr>
        {{-- <td width="180px">
            @if ($logo = _convert_image_to_base64($perusahaan->logo))
                <img src="{{ $logo }}" alt="Logo Image" width="100%" />
            @endif
        </td> --}}

        <td class="ps-10">
            <table class="table-spaced">
                <tbody>
                    <tr>
                        <td class="font-28"><strong>{{ $perusahaan->nama }}</strong></td>
                    </tr>
                    <tr>
                        <td>{{ $perusahaan->alamat }}</td>
                    </tr>
                    <tr>
                        <td>{{ $perusahaan->kota }}, {{ $perusahaan->provinsi }}, {{ $perusahaan->kode_pos }}</td>
                    </tr>
                    <tr>
                        <td>{{ $perusahaan->telp }} / {{ $perusahaan->fax }}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
</table>
