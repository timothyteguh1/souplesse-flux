<?php

namespace App\Services\Penjualan;

use App\Exceptions\GeneralException;
use App\Models\Penjualan\SuratJalan;
use App\Models\Penjualan\SuratJalanDetail;
use App\Services\System\MutasiStokService;

class SuratJalanDetailService
{
    public static function create(SuratJalan $obj, array $data = []): SuratJalanDetail
    {
        $detail = $obj->details()->create($data);

        self::validasiStok($detail);

        MutasiStokService::decreaseLatestExpiredDate(
            $obj->tanggal,
            $obj->cabang_id,
            SuratJalanDetail::class,
            $detail->id,
            SuratJalan::class,
            $obj->id,
            $obj->jenis_transaksi,
            $obj->gudang_id,
            $detail->produk_id,
            $detail->satuan_id,
            -$detail->jumlah,
            'Surat Jalan: [' . $obj->kode . ']',
        );

        return $detail;
    }

    public static function update(SuratJalanDetail $objDetail, array $data = []): bool
    {
        MutasiStokService::destroy($objDetail->mutasiStok);

        // $data['expired_date'] = _date_format_db($data['expired_date']);
        $objDetail->update($data);
        $objDetail->refresh();

        self::validasiStok($objDetail);

        MutasiStokService::decrease(
            $objDetail->header->tanggal,
            $objDetail->header->cabang_id,
            SuratJalanDetail::class,
            $objDetail->id,
            SuratJalan::class,
            $objDetail->header->id,
            $objDetail->header->jenis_transaksi,
            $objDetail->header->gudang_id,
            $objDetail->produk_id,
            $objDetail->satuan_id,
            $objDetail->expired_date,
            $objDetail->no_batch,
            -$objDetail->jumlah,
            'Surat Jalan: [' . $objDetail->header->kode . ']',
        );

        return true;
    }

    public static function validasiStok(SuratJalanDetail $detail)
    {
        // jumlah sj tidak boleh lebih dari jumlah pesanan
        if (
            $detail->pesananPenjualanDetail &&
            $detail->pesananPenjualanDetail->jumlahSuratJalan > $detail->pesananPenjualanDetail->jumlah
        ) {
            throw new GeneralException(
                sprintf(
                    "Jumlah surat jalan %s melebihi jumlah pesanan. Jumlah SO: %s, Jumlah SJ: %s]",
                    $detail->produk->nama,
                    _number($detail->pesananPenjualanDetail->jumlah),
                    _number($detail->pesananPenjualanDetail->jumlahSuratJalan),
                ),
            );
        }
    }

    public static function destroy(SuratJalanDetail $objDetail): bool
    {
        $objDetail->loadMissing('mutasiStok');

        MutasiStokService::destroy($objDetail->mutasiStok);

        $objDetail->delete();

        return true;
    }
}
