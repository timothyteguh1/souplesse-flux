<?php

namespace App\Utilities\SelectHelpers\Transaksi\Pembelian;

use App\Models\Pembelian\PesananPembelian;
use App\Models\Pembelian\PesananPembelianDetail;
use App\Models\Pembelian\ReturPembelianDetail;

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

    public static function detailProduk($pesanan_pembelian_id)
    {
        $results = [];
        $obj = PesananPembelian::find($pesanan_pembelian_id);

        if (!$obj) {
            return $results;
        }

        foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail) {
            $produkTerretur = ReturPembelianDetail::where('pesanan_pembelian_detail_id', $detail->id)->sum('jumlah');

            $results[$detail->id] = sprintf(
                "[%s] -- Tersedia %s %s",
                $detail->produk->nama,
                _number($detail->jumlah - $produkTerretur),
                $detail->satuan->nama,
            );
        }

        return $results;
    }
}
