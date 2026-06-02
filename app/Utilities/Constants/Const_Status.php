<?php

namespace App\Utilities\Constants;

class Const_Status
{
    // Master
    public const AKTIF = 'Aktif';
    public const TIDAK_AKTIF = 'Tidak Aktif';

    // Pembelian
    public const PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN = 'Menunggu Persetujuan';
    public const PESANAN_PEMBELIAN_DITOLAK = 'Ditolak';
    public const PESANAN_PEMBELIAN_BELUM_DITERIMA = 'Belum Diterima';
    public const PESANAN_PEMBELIAN_DALAM_PENGIRIMAN = 'Dalam Pengiriman';
    public const PESANAN_PEMBELIAN_SELESAI = 'Selesai';
    public const PESANAN_PEMBELIAN_DITUTUP = 'Ditutup';

    public const FAKTUR_PEMBELIAN_BELUM_LUNAS = 'Belum Lunas';
    public const FAKTUR_PEMBELIAN_LUNAS = 'Lunas';

    public const PEMBAYARAN_PEMBELIAN_AKTIF = 'Aktif';
    public const PEMBAYARAN_PEMBELIAN_TIDAK_AKTIF = 'Tidak Aktif';

    public const RETUR_PEMBELIAN_LUNAS = 'Lunas';
    public const RETUR_PEMBELIAN_BELUM_LUNAS = 'Belum Lunas';

    // Keuangan
    public const KAS_MASUK_AKTIF = 'Aktif';
    public const KAS_MASUK_TIDAK_AKTIF = 'Tidak Aktif';

    public const KAS_KELUAR_AKTIF = 'Aktif';
    public const KAS_KELUAR_TIDAK_AKTIF = 'Tidak Aktif';

    public const TRANSFER_KAS_AKTIF = 'Aktif';
    public const TRANSFER_KAS_TIDAK_AKTIF = 'Tidak Aktif';

    public const KAS_BON_BELUM_LUNAS = 'Belum Lunas';
    public const KAS_BON_LUNAS = 'Lunas';

    public const PENERIMAAN_KAS_BON_AKTIF = 'Aktif';
    public const PENERIMAAN_KAS_BON_TIDAK_AKTIF = 'Tidak Aktif';

    // Persediaan
    public const TRANSFER_PERSEDIAAN_AKTIF = 'Aktif';
    public const TRANSFER_PERSEDIAAN_TIDAK_AKTIF = 'Tidak Aktif';

    public const PENAMBAHAN_PERSEDIAAN_AKTIF = 'Aktif';
    public const PENAMBAHAN_PERSEDIAAN_TIDAK_AKTIF = 'Tidak Aktif';

    public const PENGURANGAN_PERSEDIAAN_AKTIF = 'Aktif';
    public const PENGURANGAN_PERSEDIAAN_TIDAK_AKTIF = 'Tidak Aktif';

    public const STOK_OPNAME_DALAM_PROSES = 'Dalam Proses';
    public const STOK_OPNAME_AKTIF = 'Aktif';
    public const STOK_OPNAME_SELESAI = 'Selesai';

    // Penjualan
    public const PESANAN_PENJUALAN_MENUNGGU_PERSETUJUAN = 'Menunggu Persetujuan';
    public const PESANAN_PENJUALAN_DITOLAK = 'Ditolak';

    public const PESANAN_PENJUALAN_BELUM_DICETAK = 'Belum Dicetak';
    public const PESANAN_PENJUALAN_BELUM_DIKIRIM = 'Belum Dikirim';
    public const PESANAN_PENJUALAN_BELUM_SELESAI = 'Belum Selesai';
    public const PESANAN_PENJUALAN_SELESAI = 'Selesai';
    public const PESANAN_PENJUALAN_DITUTUP = 'Ditutup';
    public const PESANAN_PENJUALAN_BATAL = 'Batal';

    public const SURAT_JALAN_BELUM_SELESAI = 'Belum Selesai';
    public const SURAT_JALAN_SELESAI = 'Selesai';

    public const FAKTUR_PENJUALAN_DALAM_PROSES = 'Dalam Proses';
    public const FAKTUR_PENJUALAN_BATAL = 'Batal';
    public const FAKTUR_PENJUALAN_BELUM_LUNAS = 'Belum Lunas';
    public const FAKTUR_PENJUALAN_LUNAS = 'Lunas';

    public const PENGIRIMAN_DALAM_PERJALANAN = 'Dalam Perjalanan';
    public const PENGIRIMAN_SELESAI = 'Selesai';

    public const PENGIRIMAN_DETAIL_BELUM_DIKIRIM = 'Belum Dikirim';
    public const PENGIRIMAN_DETAIL_DALAM_PERJALANAN = 'Dalam Perjalanan';
    public const PENGIRIMAN_DETAIL_GAGAL_ANTAR = 'Gagal Antar';
    public const PENGIRIMAN_DETAIL_SELESAI = 'Selesai';

    public const RETUR_PENJUALAN_LUNAS = 'Lunas';
    public const RETUR_PENJUALAN_BELUM_LUNAS = 'Belum Lunas';

    // Pos
    public const BUKA_KASIR_AKTIF = 'Aktif';
    public const BUKA_KASIR_TIDAK_AKTIF = 'Tidak Aktif';

    // Utang
    public const UTANG_LUNAS = 'Lunas';
    public const UTANG_BELUM_LUNAS = 'Belum Lunas';

    public const MEMO_KREDIT_LUNAS = 'Lunas';
    public const MEMO_KREDIT_BELUM_LUNAS = 'Belum Lunas';

    public const PEMBAYARAN_UTANG_AKTIF = 'Aktif';
    public const PEMBAYARAN_UTANG_TIDAK_AKTIF = 'Tidak Aktif';

    // Piutang
    public const PIUTANG_LUNAS = 'Lunas';
    public const PIUTANG_BELUM_LUNAS = 'Belum Lunas';

    public const MEMO_DEBIT_LUNAS = 'Lunas';
    public const MEMO_DEBIT_BELUM_LUNAS = 'Belum Lunas';

    public const PENERIMAAN_PIUTANG_AKTIF = 'Aktif';
    public const PENERIMAAN_PIUTANG_TIDAK_AKTIF = 'Tidak Aktif';

    //System
    public const BILLING_LUNAS = 'Lunas';
    public const BILLING_BELUM_LUNAS = 'Belum Lunas';
}
