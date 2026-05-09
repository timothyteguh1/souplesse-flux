<?php

namespace App\Utilities\SelectHelpers;

use App\Utilities\Constants\Const_JenisTransaksi;

class SH_JenisTransaksi
{
    public static function all()
    {
        $result = [];

        $result[Const_JenisTransaksi::PESANAN_PEMBELIAN] = Const_JenisTransaksi::PESANAN_PEMBELIAN;
        $result[Const_JenisTransaksi::PESANAN_PEMBELIAN_IMPOR] = Const_JenisTransaksi::PESANAN_PEMBELIAN_IMPOR;
        $result[Const_JenisTransaksi::PENERIMAAN_PEMBELIAN] = Const_JenisTransaksi::PENERIMAAN_PEMBELIAN;
        $result[Const_JenisTransaksi::PENERIMAAN_PEMBELIAN_IMPOR] = Const_JenisTransaksi::PENERIMAAN_PEMBELIAN_IMPOR;
        $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_KREDIT] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_KREDIT;
        $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_LUNAS] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_LUNAS;
        $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_IMPOR] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_IMPOR;
        $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_FORWARDER] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_FORWARDER;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SJ] = Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SJ;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SO] = Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SO;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT] = Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS] = Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM] = Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM;

        return $result;
    }

    public static function pesananPembelian()
    {
        $result = [];

        $result[Const_JenisTransaksi::PESANAN_PEMBELIAN] = Const_JenisTransaksi::PESANAN_PEMBELIAN;
        $result[Const_JenisTransaksi::PESANAN_PEMBELIAN_IMPOR] = Const_JenisTransaksi::PESANAN_PEMBELIAN_IMPOR;

        return $result;
    }

    public static function penerimaanPembelian()
    {
        $result = [];

        $result[Const_JenisTransaksi::PENERIMAAN_PEMBELIAN] = Const_JenisTransaksi::PENERIMAAN_PEMBELIAN;
        $result[Const_JenisTransaksi::PENERIMAAN_PEMBELIAN_IMPOR] = Const_JenisTransaksi::PENERIMAAN_PEMBELIAN_IMPOR;

        return $result;
    }

    public static function fakturPembelian()
    {
        $result = [];

        $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_KREDIT] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_KREDIT;
        $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_LUNAS] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_LUNAS;
        // $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_IMPOR] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_IMPOR;
        // $result[Const_JenisTransaksi::FAKTUR_PEMBELIAN_FORWARDER] = Const_JenisTransaksi::FAKTUR_PEMBELIAN_FORWARDER;

        return $result;
    }

    public static function fakturPenjualan()
    {
        $result = [];

        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT] = Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS] = Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS;

        return $result;
    }

    public static function penjualan()
    {
        $result = [];

        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SJ] = Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SJ;
        // $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SO] = Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SO;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT] = Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT;
        $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS] = Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS;
        // $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM] = Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM;

        return $result;
    }

    // public static function fakturPenjualanUmum()
    // {
    //     $result = [];

    //     $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM_KREDIT] = Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM_KREDIT;
    //     $result[Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM_LUNAS] = Const_JenisTransaksi::FAKTUR_PENJUALAN_UMUM_LUNAS;

    //     return $result;
    // }
}
