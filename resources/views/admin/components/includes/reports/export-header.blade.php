@props([
    'title',
    'file_type' => \App\Utilities\Constants\Const_Umum::FILETYPE_WEB,
])

<table class="valign-top">
    <tr>
        @php($perusahaan = \App\Models\Master\Perusahaan::find(\App\Utilities\Constants\Const_Perusahaan::PT))
        @if ($file_type != \App\Utilities\Constants\Const_Umum::FILETYPE_XLSX)
            <td width="250px">
                @if ($logo = _convert_image_to_base64($perusahaan->logo))
                    <img src="{{ $logo }}" alt="Logo Image" width="100%" />
                @endif
            </td>
        @endif

        <td>
            <table class="table-spaced">
                <tbody>
                    <tr>
                        <td class="font-24"><strong>{{ strtoupper($title) }}</strong></td>
                    </tr>
                    <tr>
                        <td class="font-20"><strong>{{ $perusahaan->nama }}</strong></td>
                    </tr>
                    {{ $slot }}
                </tbody>
            </table>
        </td>
    </tr>
</table>
