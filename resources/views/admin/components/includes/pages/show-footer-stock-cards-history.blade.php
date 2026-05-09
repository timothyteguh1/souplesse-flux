@props([
    'obj',
])

<div class="card">
    <div class="card-header bg-secondary-subtle">
        <h6 class="card-title mb-0">Stock Cards History</h6>
    </div>

    <div class="card-body">
        @php
            // header mutasi stok
            $headerMutasiStoks = \App\Models\System\MutasiStok::query()
                ->with(['gudang', 'produk', 'satuanTransaksi'])
                ->where('reference_type', get_class($obj))
                ->where('reference_id', $obj->id)
                ->get();

            // detail mutasi stok
            $detailsObjects = $obj->details;
            $detailMutasiStoks = collect();

            if ($detailsObjects) {
                foreach ($detailsObjects as $detail) {
                    $det = \App\Models\System\MutasiStok::query()
                        ->with(['gudang', 'produk', 'satuanTransaksi'])
                        ->where('reference_type', get_class($detail))
                        ->where('reference_id', $detail->id)
                        ->get();

                    $detailMutasiStoks = $detailMutasiStoks->merge($det);
                }
            }

            // mutasi stok lainnya (stok opname)
            $hasilObjects = $obj->kartuStoks;

            if ($hasilObjects) {
                foreach ($hasilObjects as $detail) {
                    $det = \App\Models\System\MutasiStok::query()
                        ->with(['gudang', 'produk', 'satuanTransaksi'])
                        ->where('reference_type', get_class($detail))
                        ->where('reference_id', $detail->id)
                        ->get();

                    $detailMutasiStoks = $detailMutasiStoks->merge($det);
                }
            }

            // total mutasi stok
            $mutasiStoks = $headerMutasiStoks->merge($detailMutasiStoks);
        @endphp

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Gudang</th>
                        <th>Produk</th>
                        <th>Keterangan</th>
                        <th class="text-end">Jumlah</th>
                        @can('lihat-hpp')
                            <th class="text-end">Harga</th>
                            <th class="text-end">Subtotal</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mutasiStoks as $mutasiStok)
                        <tr>
                            <td>{{ $mutasiStok->created_at }}</td>
                            <td>
                                @if ($mutasiStok->gudang)
                                    <a href="{{ $mutasiStok->gudang->getRouteShow() }}">
                                        [{{ $mutasiStok->gudang->kode }}] &mdash; {{ $mutasiStok->gudang->nama }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($mutasiStok->produk)
                                    <a href="{{ $mutasiStok->produk->getRouteShow() }}">
                                        [{{ $mutasiStok->produk->kode }}] &mdash; {{ $mutasiStok->produk->nama }}
                                    </a>
                                @endif
                            </td>
                            <td>{!! nl2br(e($mutasiStok->keterangan)) !!}</td>
                            <td class="text-end">
                                {{ _number($mutasiStok->jumlah_transaksi) }}

                                <a href="{{ $mutasiStok->satuanTransaksi->getRouteShow() }}">
                                    {{ $mutasiStok->satuanTransaksi->nama }}
                                </a>
                            </td>
                            @can('lihat-hpp')
                                <td class="text-end">
                                    {{ _number($mutasiStok->harga_transaksi) }}
                                </td>
                                <td class="text-end">
                                    {{ _number($mutasiStok->subtotal_transaksi) }}
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                </tbody>
                @can('lihat-hpp')
                    <tfoot>
                        <th colspan="6" class="text-end">TOTAL</th>
                        <th class="text-end">
                            {{ _number($mutasiStoks->sum('subtotal_transaksi')) }}
                        </th>
                    </tfoot>
                @endcan
            </table>
        </div>
    </div>
</div>
