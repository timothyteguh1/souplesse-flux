@php
    use App\Utilities\Constants\Const_Status;
    use App\Utilities\Constants\Const_Umum;
@endphp
<x-admin::layouts.pdf :title="'Faktur Penjualan Via SJ ' . $obj->kode">
    <div class="mb-10">
        @include('admin.components.includes.reports.perusahaan-logo')
    </div>

    <hr />

    <div class="mb-10">
        <table class="valign-top">
            <tr>
                <td class="two-third">
                    <table class="table-spaced">
                        <tbody>
                            <tr>
                                <td class="font-24"><strong>Faktur Penjualan Via SJ</strong></td>
                            </tr>
                            <tr>
                                <td>Kepada Yth.</td>
                            </tr>
                            <tr>
                                <td>{{ strtoupper($obj->customer->nama) }}</td>
                            </tr>
                            <tr>
                                <td>{{ $obj->customer->alamat }}</td>
                            </tr>
                            <tr>
                                <td>{{ $obj->customer->kota }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>

                <td class="one-third">
                    <table class="bordered valign-top">
                        <tbody>
                            <tr>
                                <td style="min-width: 150px">
                                    <div>Tanggal</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ _date_format_output($obj->tanggal) }}</strong>
                                    </div>
                                </td>
                                <td style="min-width: 150px">
                                    <div>Kode</div>
                                    <div class="ps-10 pb-10 font-10"><strong>{{ $obj->kode }}</strong></div>
                                </td>
                            </tr>
                            <tr>
                                <td style="min-width: 150px">
                                    <div>Jatuh Tempo</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ _date_format_output($obj->tanggal_jatuh_tempo) }}</strong>
                                    </div>
                                </td>
                                <td style="min-width: 150px">
                                    <div>Status</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ $obj->status }}</strong>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="separator"></div>

    @if (count($obj->details))
        <div>
            <table class="valign-top">
                <tr>
                    <td><strong>Rincian Produk</strong></td>
                </tr>
                <tr>
                    <td style="width: 100%">
                        <table class="bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" width="5%">No</th>
                                    <th class="text-center" width="30%">Nama Produk</th>
                                    <th class="text-center" width="10%">Satuan</th>
                                    <th class="text-center" width="10%">Qty</th>
                                    <th class="text-center" width="15%">Harga</th>
                                    <th class="text-center" colspan="2">Diskon</th>
                                    <th class="text-center" width="10%">Detail Diskon</th>
                                    <th class="text-center" width="15%">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->produk->nama }}</td>
                                        <td>{{ $detail->satuan->nama }}</td>
                                        <td class="text-end">
                                            {{ _number($detail->jumlah) }}
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->harga_satuan) }}
                                        </td>
                                        <td width="5%" class="text-end">
                                            {{ _number($detail->diskon_satuan_persen) }}%
                                        </td>
                                        <td width="10%" class="text-end">
                                            {{ _number($detail->diskon_satuan_rupiah) }}
                                        </td>
                                        <td>
                                            @if ($detail->diskon_satuan_1 != 0)
                                                Diskon 1:
                                                @if ($detail->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $detail->diskon_satuan_type_1 . '. ' . _number($detail->diskon_satuan_1) }}
                                                @elseif ($detail->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($detail->diskon_satuan_1) . $detail->diskon_satuan_type_1 }}
                                                @endif
                                            @endif
                                            @if ($detail->diskon_satuan_2 != 0)
                                                <br>
                                                Diskon 2:
                                                @if ($detail->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $detail->diskon_satuan_type_2 . '. ' . _number($detail->diskon_satuan_2) }}
                                                @elseif ($detail->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($detail->diskon_satuan_2) . $detail->diskon_satuan_type_2 }}
                                                @endif
                                            @endif
                                            @if ($detail->diskon_satuan_3 != 0)
                                                <br>
                                                Diskon 3:
                                                @if ($detail->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $detail->diskon_satuan_type_3 . '. ' . _number($detail->diskon_satuan_3) }}
                                                @elseif ($detail->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($detail->diskon_satuan_3) . $detail->diskon_satuan_type_3 }}
                                                @endif
                                            @endif
                                            @if ($detail->diskon_satuan_4 != 0)
                                                <br>
                                                Diskon 4:
                                                @if ($detail->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_RP)
                                                    {{ $detail->diskon_satuan_type_4 . '. ' . _number($detail->diskon_satuan_4) }}
                                                @elseif ($detail->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_PERCENT)
                                                    {{ _number($detail->diskon_satuan_4) . $detail->diskon_satuan_type_4 }}
                                                @endif
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            {{ _number($detail->subtotal) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </div>

        <div class="separator"></div>
    @endif

    <div class="offset-md-6">
        <table class="valign-top bordered">
            <tbody>
                <tr>
                    <th class="col-4">Total</th>
                    <th class="col-6 text-end">
                        {{ _number($obj->total) }}
                    </th>
                </tr>
                <tr>
                    <th class="col-4">Diskon Faktur ({{ _number($obj->diskon_persen) }}%)</th>
                    <th class="col-6 text-end">
                        {{ _number($obj->diskon_rupiah) }}
                    </th>
                </tr>
                <tr>
                    <th>DPP</th>
                    <th class="text-end">
                        {{ _number($obj->dpp) }}
                    </th>
                </tr>
                <tr>
                    <th>PPN</th>
                    <th class="text-end">
                        {{ _number($obj->ppn) }}
                    </th>
                </tr>
                <tr>
                    <th class="col-4">Total Beban</th>
                    <th class="col-6 text-end">
                        {{ _number($obj->total_beban) }}
                    </th>
                </tr>
                <tr>
                    <th>Grand Total</th>
                    <th class="text-end">
                        {{ _number($obj->grandtotal) }}
                    </th>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="separator"></div>

    <div>
        <table class="valign-top">
            <tbody>
                <tr>
                    <td>
                        <div><strong>Terbilang</strong></div>
                        <div class="ps-10 pb-20">{{ _terbilang_ucwords($obj->grandtotal) }}</div>
                    </td>
                </tr>
            </tbody>
        </table>
        @if ($obj->keterangan)
            <div class="mt-10"></div>
            <table class="valign-top">
                <tbody>
                    <tr>
                        <td>
                            <div><strong>Keterangan</strong></div>
                            <div class="ps-10">{!! nl2br(e($obj->keterangan)) !!}</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</x-admin::layouts.pdf>
