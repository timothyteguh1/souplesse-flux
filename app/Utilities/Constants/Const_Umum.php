<?php

namespace App\Utilities\Constants;

class Const_Umum
{
    public const USER_TYPE_ADMIN = 'Admin';
    public const USER_TYPE_OWNER = 'Owner';

    public const JENIS_PRODUK_PERSEDIAAN = 'Persediaan';
    public const JENIS_PRODUK_JASA = 'Jasa';
    public const JENIS_PRODUK_PAKET = 'Paket';

    public const JENIS_MUTASI_TRANSAKSI_UTANG = 'Utang';
    public const JENIS_MUTASI_TRANSAKSI_PIUTANG = 'Piutang';
    public const JENIS_MUTASI_TRANSAKSI_KAS = 'Kas';
    public const JENIS_MUTASI_TRANSAKSI_PENDAPATAN = 'Pendapatan';
    public const JENIS_MUTASI_TRANSAKSI_BEBAN = 'Beban';

    public const JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_KREDIT = 'Faktur Pembelian Kredit';
    public const JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_LUNAS = 'Faktur Pembelian Lunas';

    public const JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT = 'Faktur Penjualan Kredit';
    public const JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS = 'Faktur Penjualan Lunas';
    public const JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ = 'Faktur Penjualan Kredit Via SJ';
    public const JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SO = 'Faktur Penjualan Kredit Via SO';

    public const JENIS_TRANSAKSI_RETUR_PEMBELIAN = 'Retur Pembelian';
    public const JENIS_TRANSAKSI_RETUR_PENJUALAN = 'Retur Penjualan';

    public const JENIS_TRANSAKSI_TRANSFER_PERSEDIAAN = 'Transfer Persediaan';
    public const JENIS_TRANSAKSI_PENAMBAHAN_PERSEDIAAN = 'Penambahan Persediaan';
    public const JENIS_TRANSAKSI_PENGURANGAN_PERSEDIAAN = 'Pengurangan Persediaan';
    public const JENIS_TRANSAKSI_STOK_OPNAME = 'Stok Opname';

    public const JENIS_TRANSAKSI_KAS_BON = 'Kas Bon';
    public const JENIS_TRANSAKSI_KAS_KELUAR = 'Kas Keluar';
    public const JENIS_TRANSAKSI_KAS_MASUK = 'Kas Masuk';
    public const JENIS_TRANSAKSI_TRANSFER_KAS = 'Transfer Kas';

    public const JENIS_TRANSAKSI_MEMO_DEBIT = 'Memo Debit';
    public const JENIS_TRANSAKSI_MEMO_KREDIT = 'Memo Kredit';

    public const JENIS_TRANSAKSI_PENERIMAAN_PUTANG = 'Penerimaan Piutang';
    public const JENIS_TRANSAKSI_PEMBAYARAN_UTANG = 'Pembayaran Utang';

    public const JENIS_TRANSAKSI_STOK_AWAL = 'Stok Awal';

    public const GUDANG_UTAMA = 'Utama';
    public const GUDANG_RETUR = 'Retur';

    public const DISKON_TYPE_RP = 'Rp';
    public const DISKON_TYPE_PERCENT = '%';

    public const JENIS_SYARAT_PROMO_BELI_SEMUA_PRODUK = 'Beli Semua Produk';
    public const JENIS_SYARAT_PROMO_BELI_PRODUK_TERTENTU = 'Beli Produk Tertentu';
    public const JENIS_SYARAT_PROMO_MINIMAL_TRANSAKSI = 'Minimal Transaksi';

    public const JENIS_MANFAAT_PROMO_DISKON_PENJUALAN = 'Diskon Penjualan';
    public const JENIS_MANFAAT_PROMO_PRODUK_GRATIS = 'Produk Gratis';

    public const LOGIKA_SYARAT_PROMO_DAN = 'DAN';
    public const LOGIKA_SYARAT_PROMO_ATAU = 'ATAU';

    public const LOGIKA_MANFAAT_PROMO_DAN = 'DAN';
    public const LOGIKA_MANFAAT_PROMO_ATAU = 'ATAU';

    public const ORIENTATION_PORTRAIT = 'portrait';
    public const ORIENTATION_LANDSCAPE = 'landscape';

    public const PAPER_A0 = 'A0'; // 841 x 1189 mm
    public const PAPER_A1 = 'A1'; // 594 x 841 mm
    public const PAPER_A2 = 'A2'; // 420 x 594 mm
    public const PAPER_A3 = 'A3'; // 297 x 420 mm
    public const PAPER_A4 = 'A4'; // 210 x 297 mm, 8.26 x 11.69 inches
    public const PAPER_A5 = 'A5'; // 148 x 210 mm
    public const PAPER_A6 = 'A6'; // 105 x 148 mm
    public const PAPER_A7 = 'A7'; // 74 x 105 mm
    public const PAPER_A8 = 'A8'; // 52 x 74 mm
    public const PAPER_A9 = 'A9'; // 37 x 52 mm
    public const PAPER_B0 = 'B0'; // 1000 x 1414 mm
    public const PAPER_B1 = 'B1'; // 707 x 1000 mm
    public const PAPER_B2 = 'B2'; // 500 x 707 mm
    public const PAPER_B3 = 'B3'; // 353 x 500 mm
    public const PAPER_B4 = 'B4'; // 250 x 353 mm
    public const PAPER_B5 = 'B5'; // 176 x 250 mm, 6.93 x 9.84 inches
    public const PAPER_B6 = 'B6'; // 125 x 176 mm
    public const PAPER_B7 = 'B7'; // 88 x 125 mm
    public const PAPER_B8 = 'B7'; // 62 x 88 mm
    public const PAPER_B9 = 'B8'; // 33 x 62 mm
    public const PAPER_B10 = 'B10'; // 31 x 44 mm
    public const PAPER_C5E = 'C5E'; // 163 x 229 mm
    public const PAPER_COMM10E = 'Comm10E'; // 105 x 241 mm, U.S. Common 10 Envelope
    public const PAPER_DLE = 'DLE'; // 110 x 220 mm
    public const PAPER_EXECUTIVE = 'Executive'; // 7.5 x 10 inches, 190.5 x 254 mm
    public const PAPER_FOLIO = 'Folio'; // 210 x 330 mm
    public const PAPER_LEDGER = 'Ledger'; // 431.8 x 279.4 mm
    public const PAPER_LEGAL = 'Legal'; // 8.5 x 14 inches, 215.9 x 355.6 mm
    public const PAPER_LETTER = 'Letter'; // 8.5 x 11 inches, 215.9 x 279.4 mm
    public const PAPER_TABLOID = 'Tabloid'; // 279.4 x 431.8 mm

    public const FILETYPE_WEB = 'web';
    public const FILETYPE_PDF = 'pdf';
    public const FILETYPE_XLSX = 'xlsx';
    public const FILETYPE_HTML = 'html';
}
