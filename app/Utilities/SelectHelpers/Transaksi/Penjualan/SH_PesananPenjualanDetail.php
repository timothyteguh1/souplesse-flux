<?php

namespace App\Utilities\SelectHelpers\Transaksi\Penjualan;

use App\Models\Penjualan\PesananPenjualanDetail;

class SH_PesananPenjualanDetail
{
    public static function suratJalan($pesanan_penjualan_id, $include_ids = [])
    {
        $objs = PesananPenjualanDetail::query()
            ->with(['produk', 'satuan'])
            ->where('pesanan_penjualan_id', $pesanan_penjualan_id)
            ->when(count($include_ids), function ($query) use ($include_ids) {
                return $query->whereIn('id', $include_ids);
            }, function ($query) use ($pesanan_penjualan_id) {
                $pesananBelumTerpenuhiIds = PesananPenjualanDetail::query()
                    ->where('pesanan_penjualan_id', $pesanan_penjualan_id)
                    ->get()
                    ->where('is_terpenuhi_surat_jalan', false)
                    ->pluck('id');

                return $query->whereIn('id', $pesananBelumTerpenuhiIds);
            })
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                '[%s] -- Sisa Kirim: %s %s',
                $obj->produk->nama,
                _number($obj->sisa_surat_jalan),
                $obj->satuan->nama,
            );
        }

        return $results;
    }

    public static function active($pesanan_penjualan_id, $include_ids = [])
    {
        $objs = PesananPenjualanDetail::query()
            ->with(['produk', 'satuan'])
            ->where('pesanan_penjualan_id', $pesanan_penjualan_id)
            ->when(count($include_ids), function ($query) use ($include_ids) {
                return $query->whereIn('id', $include_ids);
            }, function ($query) use ($pesanan_penjualan_id) {
                $pesananBelumTerpenuhiIds = PesananPenjualanDetail::query()
                    ->where('pesanan_penjualan_id', $pesanan_penjualan_id)
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
