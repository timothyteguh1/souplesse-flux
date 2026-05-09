<?php

namespace App\Services\Penjualan;

use App\Models\Penjualan\FakturPenjualan;
use App\Services\System\MutasiStokService;
use App\Models\Penjualan\FakturPenjualanDetail;
use App\Exceptions\GeneralException;

class FakturPenjualanDetailService
{
    public static function create(FakturPenjualan $obj, array $data = []): FakturPenjualanDetail
    {
        $detail = $obj->details()->create($data);

        if ($obj->gudang_id) {
            MutasiStokService::decreaseLatestExpiredDate(
                $obj->tanggal,
                $obj->cabang_id,
                FakturPenjualanDetail::class,
                $detail->id,
                FakturPenjualan::class,
                $obj->id,
                $obj->jenis_transaksi,
                $obj->gudang_id,
                $detail->produk_id,
                $detail->satuan_id,
                -$detail->jumlah,
                'Faktur Penjualan: [' . $obj->kode . ']',
            );
            // MutasiStokService::decrease(
            //     $obj->tanggal,
            //     $obj->cabang_id,
            //     FakturPenjualanDetail::class,
            //     $detail->id,
            //     FakturPenjualan::class,
            //     $obj->id,
            //     $obj->jenis_transaksi,
            //     $obj->gudang_id,
            //     $detail->produk_id,
            //     $detail->satuan_id,
            //     $detail->expired_date,
            //     $detail->no_batch,
            //     -$detail->jumlah,
            //     'Faktur Penjualan: [' . $obj->kode . ']',
            // );
        }

        self::validasiStok($detail);

        $pesananPenjualan = $detail->pesananPenjualan;
        if ($pesananPenjualan) {
            PesananPenjualanService::updateStatus($pesananPenjualan);
        }


        return $detail;
    }

    public static function update(FakturPenjualanDetail $objDetail, array $data = []): bool
    {
        $objDetail->loadMissing('mutasiStoks.produk');
        //delete mutasi stok lama
        foreach ($objDetail->mutasiStoks as $value) {
            MutasiStokService::destroy($value);
        }

        $objDetail->update($data);
        $objDetail->refresh();

        if ($objDetail->header->gudang_id) {
            MutasiStokService::decreaseLatestExpiredDate(
                $objDetail->header->tanggal,
                $objDetail->header->cabang_id,
                FakturPenjualanDetail::class,
                $objDetail->id,
                FakturPenjualan::class,
                $objDetail->header->id,
                $objDetail->header->jenis_transaksi,
                $objDetail->header->gudang_id,
                $objDetail->produk_id,
                $objDetail->satuan_id,
                -$objDetail->jumlah,
                'Faktur Penjualan: [' . $objDetail->header->kode . ']',
            );
            // MutasiStokService::decrease(
            //     $objDetail->header->tanggal,
            //     $objDetail->header->cabang_id,
            //     FakturPenjualanDetail::class,
            //     $objDetail->id,
            //     FakturPenjualan::class,
            //     $objDetail->header->id,
            //     $objDetail->header->jenis_transaksi,
            //     $objDetail->header->gudang_id,
            //     $objDetail->produk_id,
            //     $objDetail->satuan_id,
            //     $objDetail->expired_date,
            //     $objDetail->no_batch,
            //     -$objDetail->jumlah,
            //     'Faktur Penjualan: [' . $objDetail->header->kode . ']',
            // );
        }

        self::validasiStok($objDetail);

        $pesananPenjualan = $objDetail->pesananPenjualan;
        if ($pesananPenjualan) {
            PesananPenjualanService::updateStatus($pesananPenjualan);
        }


        $pesananPenjualan = $objDetail->pesananPenjualan;
        if ($pesananPenjualan) {
            PesananPenjualanService::updateStatus($pesananPenjualan);
        }

        return true;
    }

    public static function destroy(FakturPenjualanDetail $objDetail): bool
    {
        $objDetail->loadMissing('mutasiStoks.produk', 'pesananPenjualan');
        //delete mutasi stok lama
        foreach ($objDetail->mutasiStoks as $value) {
            MutasiStokService::destroy($value);
        }

        //ini udah gk kepake, kepake kalo misal 1 faktur penjualan bisa ambil lebih dari 1 SO
        // $pesananPenjualan = $objDetail->pesananPenjualan;

        // if ($pesananPenjualan) {
        //     PesananPenjualanService::updateStatus($pesananPenjualan);
        // }

        $objDetail->delete();
        return true;
    }

    public static function validasiStok(FakturPenjualanDetail $detail)
    {
        // tidak boleh lebih dari surat jalan
        if (
            $detail->suratJalanDetail
            && $detail->suratJalanDetail->jumlah_faktur > $detail->suratJalanDetail->jumlah
        ) {
            throw new GeneralException(
                sprintf(
                    "Jumlah faktur %s melebihi jumlah surat jalan. Jumlah SJ: %s, Jumlah Faktur: %s]",
                    $detail->produk->nama,
                    _number($detail->suratJalanDetail->jumlah),
                    _number($detail->suratJalanDetail->jumlah_faktur),
                ),
            );
        }

        // tidak boleh lebih dari pesanan penjualan
        if (
            $detail->pesananPenjualanDetail &&
            $detail->pesananPenjualanDetail->jumlah_faktur > $detail->pesananPenjualanDetail->jumlah
        ) {
            throw new GeneralException(
                sprintf(
                    "Jumlah faktur %s melebihi jumlah pesanan. Jumlah SO: %s, Jumlah Faktur: %s",
                    $detail->produk->nama,
                    _number($detail->pesananPenjualanDetail->jumlah),
                    _number($detail->pesananPenjualanDetail->jumlah_faktur),
                ),
            );
        }
    }
}
