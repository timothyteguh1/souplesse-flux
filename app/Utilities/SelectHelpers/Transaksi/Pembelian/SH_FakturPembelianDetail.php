<?php

namespace App\Utilities\SelectHelpers\Transaksi\Pembelian;

use App\Models\Pembelian\FakturPembelian;
use App\Models\Pembelian\ReturPembelianDetail;

class SH_FakturPembelianDetail
{
    public static function detailProduk($faktur_pembelian_id)
    {
        $results = [];
        $obj = FakturPembelian::find($faktur_pembelian_id);

        if (!$obj) {
            return $results;
        }

        foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail) {
            $produkTerretur = ReturPembelianDetail::where('faktur_pembelian_detail_id', $detail->id)->sum('jumlah');

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
