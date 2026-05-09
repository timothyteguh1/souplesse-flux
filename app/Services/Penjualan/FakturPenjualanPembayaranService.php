<?php

namespace App\Services\Penjualan;

use App\Models\Master\Kas;
use App\Models\Penjualan\FakturPenjualan;
use App\Models\Penjualan\FakturPenjualanPembayaran;
use App\Services\System\MutasiTransaksiService;
use App\Utilities\Constants\Const_Umum;

class FakturPenjualanPembayaranService
{
    public static function create(FakturPenjualan $obj, array $data = []): FakturPenjualanPembayaran
    {
        $detail = $obj->pembayarans()->create($data);

        MutasiTransaksiService::increase(
            $obj->tanggal,
            $obj->cabang_id,
            Const_Umum::JENIS_MUTASI_TRANSAKSI_KAS,
            Kas::class,
            $detail->kas_id,
            FakturPenjualanPembayaran::class,
            $detail->id,
            FakturPenjualan::class,
            $obj->id,
            $obj->jenis_transaksi,
            $detail->jumlah,
            'Faktur Penjualan : [' . $obj->kode . ']',
        );

        return $detail;
    }

    public static function update(FakturPenjualanPembayaran $objDetail, array $data = []): bool
    {
        $objDetail->loadMissing('mutasiTransaksis');
        foreach ($objDetail->mutasiTransaksis as $mutasiTransaksi) {
            MutasiTransaksiService::destroy($mutasiTransaksi);
        }

        $objDetail->update($data);
        $objDetail->refresh();

        MutasiTransaksiService::increase(
            $objDetail->fakturPenjualan->tanggal,
            $objDetail->fakturPenjualan->cabang_id,
            Const_Umum::JENIS_MUTASI_TRANSAKSI_KAS,
            Kas::class,
            $objDetail->kas_id,
            FakturPenjualanPembayaran::class,
            $objDetail->id,
            FakturPenjualan::class,
            $objDetail->fakturPenjualan->id,
            $objDetail->fakturPenjualan->jenis_transaksi,
            $objDetail->jumlah,
            'Faktur Penjualan : [' . $objDetail->fakturPenjualan->kode . ']',
        );

        return true;
    }

    public static function destroy(FakturPenjualanPembayaran $objDetail): bool
    {
        $objDetail->loadMissing('mutasiTransaksis');
        foreach ($objDetail->mutasiTransaksis as $mutasiTransaksi) {
            MutasiTransaksiService::destroy($mutasiTransaksi);
        }

        return $objDetail->delete();
    }
}
