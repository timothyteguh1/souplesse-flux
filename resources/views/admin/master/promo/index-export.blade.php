@php
    use App\Utilities\Constants\Const_Umum;
@endphp
<x-admin::layouts.export>
    <table class="bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Promo</th>
                <th>Periode Promo</th>
                <th>Diskon Satuan 1</th>
                <th>Diskon Satuan 2</th>
                <th>Diskon Satuan 3</th>
                <th>Diskon Satuan 4</th>
                <th>Promo Grosir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $obj)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $obj->kode }}</td>
                    <td>{{ $obj->nama }}</td>
                    <td>
                        {{ _date_format_output($obj->tanggal_awal) }} -
                        {{ _date_format_output($obj->tanggal_akhir) }}
                    </td>
                    <td class="text-end">
                        @if ($obj->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_RP)
                            {{ $obj->diskon_satuan_type_1 . '. ' . _number($obj->diskon_satuan_1) }}
                        @elseif ($obj->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_PERCENT)
                            {{ _number($obj->diskon_satuan_1) . $obj->diskon_satuan_type_1 }}
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($obj->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_RP)
                            {{ $obj->diskon_satuan_type_2 . '. ' . _number($obj->diskon_satuan_2) }}
                        @elseif ($obj->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_PERCENT)
                            {{ _number($obj->diskon_satuan_2) . $obj->diskon_satuan_type_2 }}
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($obj->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_RP)
                            {{ $obj->diskon_satuan_type_3 . '. ' . _number($obj->diskon_satuan_3) }}
                        @elseif ($obj->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_PERCENT)
                            {{ _number($obj->diskon_satuan_3) . $obj->diskon_satuan_type_3 }}
                        @endif
                    </td>
                    <td class="text-end">
                        @if ($obj->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_RP)
                            {{ $obj->diskon_satuan_type_4 . '. ' . _number($obj->diskon_satuan_4) }}
                        @elseif ($obj->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_PERCENT)
                            {{ _number($obj->diskon_satuan_4) . $obj->diskon_satuan_type_4 }}
                        @endif
                    </td>
                    <td>{{ $obj->is_promo_grosir ? 'Ya' : 'Tidak' }}</td>
                    <td>{{ $obj->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-admin::layouts.export>
