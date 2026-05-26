<?php

namespace App\Utilities\SelectHelpers\System;

use App\Utilities\Constants\Const_Status;
use Illuminate\Support\Collection;

class SH_Status
{
    public static function common(): Collection
    {
        $result = [];

        $result[Const_Status::AKTIF] = Const_Status::AKTIF;
        $result[Const_Status::TIDAK_AKTIF] = Const_Status::TIDAK_AKTIF;

        return collect($result);
    }

    // Pembelian
    public static function pesanan_pembelian(): Collection
    {
        $result = [];

        $result[Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN] = Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN;
        $result[Const_Status::PESANAN_PEMBELIAN_DITOLAK] = Const_Status::PESANAN_PEMBELIAN_DITOLAK;
        $result[Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA] = Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA;
        $result[Const_Status::PESANAN_PEMBELIAN_DALAM_PENGIRIMAN] = Const_Status::PESANAN_PEMBELIAN_DALAM_PENGIRIMAN;
        $result[Const_Status::PESANAN_PEMBELIAN_SELESAI] = Const_Status::PESANAN_PEMBELIAN_SELESAI;
        $result[Const_Status::PESANAN_PEMBELIAN_DITUTUP] = Const_Status::PESANAN_PEMBELIAN_DITUTUP;

        return collect($result);
    }

    public static function faktur_pembelian(): Collection
    {
        $result = [];

        $result[Const_Status::FAKTUR_PEMBELIAN_BELUM_LUNAS] = Const_Status::FAKTUR_PEMBELIAN_BELUM_LUNAS;
        $result[Const_Status::FAKTUR_PEMBELIAN_LUNAS] = Const_Status::FAKTUR_PEMBELIAN_LUNAS;

        return collect($result);
    }

    public static function pembayaran_pembelian(): Collection
    {
        $result = [];

        $result[Const_Status::PEMBAYARAN_PEMBELIAN_AKTIF] = Const_Status::PEMBAYARAN_PEMBELIAN_AKTIF;
        $result[Const_Status::PEMBAYARAN_PEMBELIAN_TIDAK_AKTIF] = Const_Status::PEMBAYARAN_PEMBELIAN_TIDAK_AKTIF;

        return collect($result);
    }

    // Persediaan
    public static function transfer_persediaan(): Collection
    {
        $result = [];

        $result[Const_Status::TRANSFER_PERSEDIAAN_AKTIF] = Const_Status::TRANSFER_PERSEDIAAN_AKTIF;
        $result[Const_Status::TRANSFER_PERSEDIAAN_TIDAK_AKTIF] = Const_Status::TRANSFER_PERSEDIAAN_TIDAK_AKTIF;

        return collect($result);
    }

    public static function penambahan_persediaan(): Collection
    {
        $result = [];

        $result[Const_Status::PENAMBAHAN_PERSEDIAAN_AKTIF] = Const_Status::PENAMBAHAN_PERSEDIAAN_AKTIF;
        $result[Const_Status::PENAMBAHAN_PERSEDIAAN_TIDAK_AKTIF] = Const_Status::PENAMBAHAN_PERSEDIAAN_TIDAK_AKTIF;

        return collect($result);
    }

    public static function pengurangan_persediaan(): Collection
    {
        $result = [];

        $result[Const_Status::PENGURANGAN_PERSEDIAAN_AKTIF] = Const_Status::PENGURANGAN_PERSEDIAAN_AKTIF;
        $result[Const_Status::PENGURANGAN_PERSEDIAAN_TIDAK_AKTIF] = Const_Status::PENGURANGAN_PERSEDIAAN_TIDAK_AKTIF;

        return collect($result);
    }

    public static function stok_opname(): Collection
    {
        $result = [];

        $result[Const_Status::STOK_OPNAME_DALAM_PROSES] = Const_Status::STOK_OPNAME_DALAM_PROSES;
        $result[Const_Status::STOK_OPNAME_AKTIF] = Const_Status::STOK_OPNAME_AKTIF;
        $result[Const_Status::STOK_OPNAME_SELESAI] = Const_Status::STOK_OPNAME_SELESAI;

        return collect($result);
    }

    // Keuangan
    public static function kas_masuk(): Collection
    {
        $result = [];

        $result[Const_Status::KAS_MASUK_AKTIF] = Const_Status::KAS_MASUK_AKTIF;
        $result[Const_Status::KAS_MASUK_TIDAK_AKTIF] = Const_Status::KAS_MASUK_TIDAK_AKTIF;

        return collect($result);
    }

    public static function kas_keluar(): Collection
    {
        $result = [];

        $result[Const_Status::KAS_KELUAR_AKTIF] = Const_Status::KAS_KELUAR_AKTIF;
        $result[Const_Status::KAS_KELUAR_TIDAK_AKTIF] = Const_Status::KAS_KELUAR_TIDAK_AKTIF;

        return collect($result);
    }

    public static function transfer_kas(): Collection
    {
        $result = [];

        $result[Const_Status::TRANSFER_KAS_AKTIF] = Const_Status::TRANSFER_KAS_AKTIF;
        $result[Const_Status::TRANSFER_KAS_TIDAK_AKTIF] = Const_Status::TRANSFER_KAS_TIDAK_AKTIF;

        return collect($result);
    }

    public static function kas_bon(): Collection
    {
        $result = [];

        $result[Const_Status::KAS_BON_BELUM_LUNAS] = Const_Status::KAS_BON_BELUM_LUNAS;
        $result[Const_Status::KAS_BON_LUNAS] = Const_Status::KAS_BON_LUNAS;

        return collect($result);
    }

    public static function penerimaan_kas_bon(): Collection
    {
        $result = [];

        $result[Const_Status::PENERIMAAN_KAS_BON_AKTIF] = Const_Status::PENERIMAAN_KAS_BON_AKTIF;
        $result[Const_Status::PENERIMAAN_KAS_BON_TIDAK_AKTIF] = Const_Status::PENERIMAAN_KAS_BON_TIDAK_AKTIF;

        return collect($result);
    }


    // Penjualan
    public static function pesanan_penjualan(): Collection
    {
        $result = [];

        $result[Const_Status::PESANAN_PENJUALAN_BELUM_DIKIRIM] = Const_Status::PESANAN_PENJUALAN_BELUM_DIKIRIM;
        $result[Const_Status::PESANAN_PENJUALAN_BELUM_DICETAK] = Const_Status::PESANAN_PENJUALAN_BELUM_DICETAK;
        $result[Const_Status::PESANAN_PENJUALAN_SELESAI] = Const_Status::PESANAN_PENJUALAN_SELESAI;
        $result[Const_Status::PESANAN_PENJUALAN_BATAL] = Const_Status::PESANAN_PENJUALAN_BATAL;

        return collect($result);
    }

    public static function surat_jalan()
    {
        $result = [];

        $result[Const_Status::SURAT_JALAN_BELUM_SELESAI] = Const_Status::SURAT_JALAN_BELUM_SELESAI;
        $result[Const_Status::SURAT_JALAN_SELESAI] = Const_Status::SURAT_JALAN_SELESAI;

        return $result;
    }

    public static function pengiriman()
    {
        $result = [];

        $result[Const_Status::PENGIRIMAN_DALAM_PERJALANAN] = Const_Status::PENGIRIMAN_DALAM_PERJALANAN;
        $result[Const_Status::PENGIRIMAN_SELESAI] = Const_Status::PENGIRIMAN_SELESAI;

        return $result;
    }

    public static function pengirimanUpdateDetail()
    {
        $result = [];

        $result[Const_Status::PENGIRIMAN_DETAIL_GAGAL_ANTAR] = Const_Status::PENGIRIMAN_DETAIL_GAGAL_ANTAR;
        $result[Const_Status::PENGIRIMAN_DETAIL_SELESAI] = Const_Status::PENGIRIMAN_DETAIL_SELESAI;

        return $result;
    }

    public static function faktur_penjualan(): Collection
    {
        $result = [];

        $result[Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS] = Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS;
        $result[Const_Status::FAKTUR_PENJUALAN_LUNAS] = Const_Status::FAKTUR_PENJUALAN_LUNAS;

        return collect($result);
    }

    // Pos
    public static function buka_kasir(): Collection
    {
        $result = [];

        $result[Const_Status::BUKA_KASIR_AKTIF] = Const_Status::BUKA_KASIR_AKTIF;
        $result[Const_Status::BUKA_KASIR_TIDAK_AKTIF] = Const_Status::BUKA_KASIR_TIDAK_AKTIF;

        return collect($result);
    }

    // Utang
    public static function utang(): Collection
    {
        $result = [];

        $result[Const_Status::UTANG_LUNAS] = Const_Status::UTANG_LUNAS;
        $result[Const_Status::UTANG_BELUM_LUNAS] = Const_Status::UTANG_BELUM_LUNAS;

        return collect($result);
    }

    public static function pembayaranUtang(): Collection
    {
        $result = [];

        $result[Const_Status::PEMBAYARAN_UTANG_AKTIF] = Const_Status::PEMBAYARAN_UTANG_AKTIF;
        $result[Const_Status::PEMBAYARAN_UTANG_TIDAK_AKTIF] = Const_Status::PEMBAYARAN_UTANG_TIDAK_AKTIF;

        return collect($result);
    }

    // Piutang
    public static function piutang(): Collection
    {
        $result = [];

        $result[Const_Status::PIUTANG_LUNAS] = Const_Status::PIUTANG_LUNAS;
        $result[Const_Status::PIUTANG_BELUM_LUNAS] = Const_Status::PIUTANG_BELUM_LUNAS;

        return collect($result);
    }

    public static function memoDebit(): Collection
    {
        $result = [];

        $result[Const_Status::MEMO_DEBIT_LUNAS] = Const_Status::MEMO_DEBIT_LUNAS;
        $result[Const_Status::MEMO_DEBIT_BELUM_LUNAS] = Const_Status::MEMO_DEBIT_BELUM_LUNAS;

        return collect($result);
    }

    public static function penerimaanPiutang(): Collection
    {
        $result = [];

        $result[Const_Status::PENERIMAAN_PIUTANG_AKTIF] = Const_Status::PENERIMAAN_PIUTANG_AKTIF;
        $result[Const_Status::PENERIMAAN_PIUTANG_TIDAK_AKTIF] = Const_Status::PENERIMAAN_PIUTANG_TIDAK_AKTIF;

        return collect($result);
    }

    public static function memoKredit(): Collection
    {
        $result = [];

        $result[Const_Status::MEMO_KREDIT_LUNAS] = Const_Status::MEMO_KREDIT_LUNAS;
        $result[Const_Status::MEMO_KREDIT_BELUM_LUNAS] = Const_Status::MEMO_KREDIT_BELUM_LUNAS;

        return collect($result);
    }

    public static function statusPengiriman(): Collection
    {
        $result = [];

        $result[Const_Status::PENGIRIMAN_DETAIL_BELUM_DIKIRIM] = Const_Status::PENGIRIMAN_DETAIL_BELUM_DIKIRIM;
        $result[Const_Status::PENGIRIMAN_DETAIL_DALAM_PERJALANAN] = Const_Status::PENGIRIMAN_DETAIL_DALAM_PERJALANAN;
        $result[Const_Status::PENGIRIMAN_DETAIL_GAGAL_ANTAR] = Const_Status::PENGIRIMAN_DETAIL_GAGAL_ANTAR;
        $result[Const_Status::PENGIRIMAN_DETAIL_SELESAI] = Const_Status::PENGIRIMAN_DETAIL_SELESAI;

        return collect($result);
    }
}
