<?php

namespace App\Services\Persediaan;

use App\Models\Persediaan\PenambahanPersediaan;
use App\Models\Persediaan\PenambahanPersediaanDetail;
use App\Services\System\MutasiStokService;
use App\Utilities\Constants\Const_Umum;

class PenambahanPersediaanDetailService
{
    public static function create(PenambahanPersediaan $obj, array $data = []): PenambahanPersediaanDetail
    {
        $data['expired_date'] = _date_format_db($data['expired_date']);
        $detail = $obj->details()->create($data);
        MutasiStokService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            PenambahanPersediaanDetail::class,
            $detail->id,
            PenambahanPersediaan::class,
            $obj->id,
            Const_Umum::JENIS_TRANSAKSI_PENAMBAHAN_PERSEDIAAN,
            $obj->gudang_id,
            $detail->produk_id,
            $detail->satuan_id,
            $detail->expired_date,
            $detail->no_batch,
            $detail->jumlah,
            $detail->harga_satuan,
            'Penambahan Persediaan: [' . $obj->kode . ']',
        );

        return $detail;
    }

    public static function update(PenambahanPersediaanDetail $objDetail, array $data = []): bool
    {
        $objDetail->loadMissing('mutasiStoks.produk');
        //delete mutasi stok lama
        foreach ($objDetail->mutasiStoks as $value) {
            MutasiStokService::destroy($value);
        }

        $data['expired_date'] = _date_format_db($data['expired_date']);
        $objDetail->update($data);
        $objDetail->refresh();

        MutasiStokService::increase(
            $objDetail->penambahanPersediaan->tanggal,
            $objDetail->penambahanPersediaan->cabang_id,
            PenambahanPersediaanDetail::class,
            $objDetail->id,
            PenambahanPersediaan::class,
            $objDetail->penambahanPersediaan->id,
            Const_Umum::JENIS_TRANSAKSI_PENAMBAHAN_PERSEDIAAN,
            $objDetail->penambahanPersediaan->gudang_id,
            $objDetail->produk_id,
            $objDetail->satuan_id,
            $objDetail->expired_date,
            $objDetail->no_batch,
            $objDetail->jumlah,
            $objDetail->harga_satuan,
            'Penambahan Persediaan: [' . $objDetail->penambahanPersediaan->kode . ']',
        );

        return true;
    }

    public static function destroy(PenambahanPersediaanDetail $objDetail): bool
    {
        $objDetail->loadMissing('mutasiStoks.produk');
        //delete mutasi stok lama
        foreach ($objDetail->mutasiStoks as $value) {
            MutasiStokService::destroy($value);
        }

        return $objDetail->delete();
    }
}
