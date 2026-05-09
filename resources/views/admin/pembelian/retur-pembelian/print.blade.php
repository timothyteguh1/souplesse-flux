<x-admin::layouts.pdf :title="'Retur Pembelian ' . $obj->kode">
    <div class="mb-10">
        @include('admin.components.includes.reports.perusahaan-logo')
    </div>

    <hr />

    <div class="mb-10">
        <table class="valign-top">
            <tr>
                <td class="font-24"><strong>Retur Pembelian</strong></td>
            </tr>
            <tr>
                <td class="two-third">
                    <table class="table-spaced">
                        <tbody>
                            <tr>
                                <td>Diterbitkan</td>
                            </tr>
                            <tr>
                                <td>{{ strtoupper($obj->supplier->nama) }}</td>
                            </tr>
                            <tr>
                                <td>{{ $obj->supplier->alamat }}</td>
                            </tr>
                            <tr>
                                <td>{{ $obj->supplier->kota }}</td>
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
                                        <strong>
                                            {{ _date_format_output($obj->tanggal) }}
                                        </strong>
                                    </div>
                                </td>
                                <td style="min-width: 150px">
                                    <div>Kode</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ $obj->kode }}</strong>
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
                                <th class="text-center" width="20%">Nama Produk</th>
                                <th class="text-center" width="10%">Qty</th>
                                <th class="text-center" width="10%">Satuan</th>
                                <th class="text-center" width="20%">Harga</th>
                                <th class="text-center" width="20%">Diskon</th>
                                <th class="text-center" width="20%">Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $detail->produk->nama }}
                                    </td>
                                    <td class="text-right">
                                        {{ _number($detail->jumlah) }}
                                    </td>
                                    <td>
                                        {{ $detail->satuan->nama }}
                                    </td>
                                    <td class="text-right">
                                        {{ _number($detail->harga_satuan) }}
                                    </td>
                                    <td class="text-right">
                                        {{ _number($detail->diskon_satuan) }}
                                    </td>
                                    <td class="text-right">
                                        {{ _number($detail->subtotal) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="text-right" colspan="6">Total</th>
                                <th class="text-right">{{ _number($obj->total) }}</th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">DPP</th>
                                <th class="text-right">{{ _number($obj->dpp) }}</th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">PPN ({{ _number($obj->ppn_percent) }}%)</th>
                                <th class="text-right">{{ _number($obj->ppn) }}</th>
                            </tr>
                            <tr>
                                <th class="text-right" colspan="6">Grand Total</th>
                                <th class="text-right">{{ _number($obj->grandtotal) }}</th>
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
                        <div class="ps-10 pb-20">
                            {{ _terbilang_ucwords($obj->grandtotal) }}
                        </div>
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
                            <div class="ps-10">
                                {!! nl2br(e($obj->keterangan)) !!}
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        @endif
    </div>
</x-admin::layouts.pdf>
