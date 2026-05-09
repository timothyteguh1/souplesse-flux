<?php

namespace App\Services\Persediaan;

use App\Models\Persediaan\PenguranganPersediaan;
use App\Models\Persediaan\PenguranganPersediaanDetail;
use App\Services\System\MutasiStokService;
use App\Utilities\Constants\Const_Umum;

class PenguranganPersediaanDetailService
{
    public static function create(PenguranganPersediaan $obj, array $data = []): PenguranganPersediaanDetail
    {
        $data['expired_date'] = $data['expired_date'] != "-" ? $data['expired_date'] : null;
        $data['no_batch'] = $data['no_batch'] != "-" ? $data['no_batch'] : null;
        $detail = $obj->details()->create($data);
        MutasiStokService::decrease(
            $obj->tanggal,
            $obj->cabang_id,
            PenguranganPersediaanDetail::class,
            $detail->id,
            PenguranganPersediaan::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_PENGURANGAN_PERSEDIAAN,
            $obj->gudang_id,
            $detail->produk_id,
            $detail->satuan_id,
            $detail->expired_date,
            $detail->no_batch,
            -$detail->jumlah,
            'Pengurangan Persediaan: [' . $obj->kode . ']',
        );

        return $detail;
    }

    public static function update(PenguranganPersediaanDetail $objDetail, array $data = []): bool
    {
        $objDetail->loadMissing('mutasiStoks.produk');
        //delete mutasi stok lama
        foreach ($objDetail->mutasiStoks as $value) {
            MutasiStokService::destroy($value);
        }
        $data['expired_date'] = $data['expired_date'] != "-" ? $data['expired_date'] : null;
        $data['no_batch'] = $data['no_batch'] != "-" ? $data['no_batch'] : null;
        $objDetail->update($data);
        $objDetail->refresh();

        MutasiStokService::decrease(
            $objDetail->penguranganPersediaan->tanggal,
            $objDetail->penguranganPersediaan->cabang_id,
            PenguranganPersediaanDetail::class,
            $objDetail->id,
            PenguranganPersediaan::class,
            $objDetail->penguranganPersediaan->id,
            Const_Umum::JENIS_TRANSAKSI_PENGURANGAN_PERSEDIAAN,
            $objDetail->penguranganPersediaan->gudang_id,
            $objDetail->produk_id,
            $objDetail->satuan_id,
            $objDetail->expired_date,
            $objDetail->no_batch,
            -$objDetail->jumlah,
            'Pengurangan Persediaan: [' . $objDetail->penguranganPersediaan->kode . ']',
        );

        return true;
    }

    public static function destroy(PenguranganPersediaanDetail $objDetail): bool
    {
        $objDetail->loadMissing('mutasiStoks.produk');
        //delete mutasi stok lama
        foreach ($objDetail->mutasiStoks as $value) {
            MutasiStokService::destroy($value);
        }

        return $objDetail->delete();
    }
}
