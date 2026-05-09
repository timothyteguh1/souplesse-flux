<?php

namespace App\Utilities\SelectHelpers\Transaksi\Penjualan;

use App\Models\Penjualan\FakturPenjualan;

class SH_FakturPenjualanDetail
{
    public static function detailProduk($faktur_penjualan_id)
    {
        $results = [];
        $obj = FakturPenjualan::find($faktur_penjualan_id);

        if (!$obj) {
            return $results;
        }

        foreach ($obj->details()->with(['produk', 'satuan'])->get() as $detail) {
            $results[$detail->id] = sprintf(
                "[%s] -- Tersedia %s %s",
                $detail->produk->nama,
                _number($detail->jumlah),
                $detail->satuan->nama,
            );
        }

        return $results;
    }
}
