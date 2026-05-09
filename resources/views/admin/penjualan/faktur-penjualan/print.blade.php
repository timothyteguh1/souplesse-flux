<x-admin::layouts.pdf :title="'Faktur Penjualan ' . $obj->kode">
    <div class="mb-10">
        @include('admin.components.includes.reports.perusahaan-logo')
    </div>

    <hr />

    <div class="mb-10">
        <table class="valign-top">
            <tr>
                <td class="font-24" colspan="2"><strong>Faktur Penjualan</strong></td>
            </tr>
            <tr>
                <td class="two-third">
                    <table class="table-spaced">
                        <tbody>
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
                                    <div>Gudang</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ $obj->gudang->nama }}</strong>
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

    <div>
        <table class="valign-top">
            <tr>
                <td><strong>Rincian</strong></td>
            </tr>
            <tr>
                <td style="width: 100%">
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center" width="30%">Produk</th>
                                <th class="text-center" width="10%">Satuan</th>
                                <th class="text-center" width="10%">Qty</th>
                                <th class="text-center" width="15%">Harga</th>
                                <th class="text-center" width="15%">Diskon</th>
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
                                    <td class="text-end">
                                        {{ _number($detail->diskon_satuan_persen) }} %
                                    </td>
                                    <td class="text-end">
                                        {{ _number($detail->subtotal) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="6">Total</th>
                                <th class="text-right">
                                    {{ _number($obj->total) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">Diskon
                                    Faktur({{ _number($obj->diskon_persen) }}%)</th>
                                <th class="text-right">
                                    {{ _number($obj->diskon_rupiah) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">DPP</th>
                                <th class="text-right">
                                    {{ _number($obj->dpp) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">PPN</th>
                                <th class="text-right">
                                    {{ _number($obj->ppn) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">Total Beban</th>
                                <th class="text-right">
                                    {{ _number($obj->total_beban) }}
                                </th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">Grand Total</th>
                                <th class="text-right">
                                    {{ _number($obj->grandtotal) }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
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
