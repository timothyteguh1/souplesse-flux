@php
    $cabang = $obj->cabang;
    $logo = $cabang->getFirstMediaPath('default', 'thumbnail');
    $logoBase64 = _convert_image_to_base64($logo);
@endphp

<table class="valign-top">
    <tr>
        @if ($logoBase64)
            <td width="180px">
                <img src="{{ $logoBase64 }}" alt="Logo Image" width="100%" />
            </td>
        @endif

        <td class="ps-10">
            <table class="table-spaced">
                <tbody>
                    <tr>
                        <td class="font-28"><strong>{{ $cabang->nama }}</strong></td>
                    </tr>
                    @if ($cabang->alamat)
                        <tr>
                            <td>{{ $cabang->alamat }}</td>
                        </tr>
                    @endif

                    @if ($cabang->kota)
                        <tr>
                            <td>{{ $cabang->kota }}, {{ $cabang->provinsi }}, {{ $cabang->kode_pos }}</td>
                        </tr>
                    @endif

                    @if ($cabang->telp)
                        <tr>
                            <td>{{ $cabang->telp }}</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>
