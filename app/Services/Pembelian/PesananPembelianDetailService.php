<?php

namespace App\Services\Pembelian;

use App\Models\Master\Gudang;
use App\Models\Pembelian\PesananPembelian;
use App\Models\Pembelian\PesananPembelianDetail;
use App\Services\System\MutasiStokService;

class PesananPembelianDetailService
{
    public static function create(PesananPembelian $obj, array $data = []): PesananPembelianDetail
    {
        $detail = $obj->details()->create($data);

        return $detail;
    }

    public static function update(PesananPembelian $obj, array $data = []): bool
    {
        return $obj->details()->where('id', $data['id'])->update($data);
    }

    public static function destroy(PesananPembelianDetail $objDetail): bool
    {
        return $objDetail->delete();
    }

    public static function selesai(PesananPembelian $obj, PesananPembelianDetail $detail): bool
    {
        MutasiStokService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            PesananPembelianDetail::class,
            $detail->id,
            PesananPembelian::class,
            $obj->id,
            $obj->jenis_transaksi ?: "Pesanan Pembelian",
            $obj->gudang_id ?: Gudang::first()->id,
            $detail->produk_id,
            $detail->satuan_id,
            $detail->expired_date,
            $detail->no_batch,
            $detail->jumlah,
            $detail->dpp_satuan,
            'Pesanan Pembelian: [' . $obj->kode . ']',
        );

        return true;
    }
}
