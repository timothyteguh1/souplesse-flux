<?php

namespace App\Utilities\SelectHelpers\Transaksi\Pembelian;

use App\Models\Pembelian\PesananPembelianDetail;

class SH_PesananPembelianDetail
{
    public static function active($pesanan_pembelian_id, $include_ids = [])
    {
        $objs = PesananPembelianDetail::query()
            ->with(['produk', 'satuan'])
            ->where('pesanan_pembelian_id', $pesanan_pembelian_id)
            ->when(count($include_ids), function ($query) use ($include_ids) {
                return $query->whereIn('id', $include_ids);
            }, function ($query) use ($pesanan_pembelian_id) {
                $pesananBelumTerpenuhiIds = PesananPembelianDetail::query()
                    ->where('pesanan_pembelian_id', $pesanan_pembelian_id)
                    ->get()
                    ->where('is_terpenuhi', false)
                    ->pluck('id');

                return $query->whereIn('id', $pesananBelumTerpenuhiIds);
            })
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                '[%s] -- Sisa Faktur: %s %s',
                $obj->produk->nama,
                _number($obj->sisa_faktur),
                $obj->satuan->nama,
            );
        }

        return $results;
    }
}
