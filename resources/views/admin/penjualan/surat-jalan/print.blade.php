<x-admin::layouts.pdf :title="'Surat Jalan ' . $obj->kode">
    <div class="mb-10">
        @include('admin.components.includes.reports.cabang')
    </div>

    <hr />

    <div class="mb-10">
        <table class="valign-top">
            <tr>
                <td class="two-third">
                    <table class="table-spaced">
                        <tbody>
                            <tr>
                                <td class="font-24"><strong>Surat Jalan</strong></td>
                            </tr>
                            <tr>
                                <td>Diterbitkan</td>
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
                            <tr>
                                <td style="min-width: 150px">
                                    <div>Gudang</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ $obj->gudang?->nama }}</strong>
                                    </div>
                                </td>
                                <td style="min-width: 150px">
                                    <div>Ekspedisi</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ $obj->ekspedisi?->nama }}</strong>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td style="min-width: 150px">
                                    <div>No Polisi</div>
                                    <div class="ps-10 pb-10 font-10">
                                        <strong>{{ $obj->no_polisi }}</strong>
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

    <div>
        <table class="valign-top">
            <tr>
                <td style="width: 100%">
                    <table class="bordered">
                        <thead>
                            <tr>
                                <th width="5%" class="text-center">#</th>
                                <th width="25%" class="text-center">Pesanan Penjualan</th>
                                <th width="20%" class="text-center">Produk</th>
                                <th width="20%" class="text-center">Satuan</th>
                                <th width="15%" class="text-center">Qty</th>
                                <th width="15%" class="text-center">Qty Koli</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($obj->details()->with(['pesananPenjualanDetail.header', 'produk', 'satuan'])->get() as $detail)
                                <tr>
                                    <td class="text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ $detail->pesananPenjualanDetail->header->kode }}
                                    </td>
                                    <td>
                                        {{ $detail->produk->nama }}
                                    </td>
                                    <td>
                                        {{ $detail->satuan->nama }}
                                    </td>
                                    <td class="text-end">
                                        {{ _number($detail->jumlah) }}
                                    </td>
                                    <td class="text-end">
                                        {{ _number($detail->jumlah_koli) }}
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

    <div>
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
