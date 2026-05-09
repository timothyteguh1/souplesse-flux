<?php

namespace App\Utilities\Constants;

class Const_JenisTransaksi
{
    // Pembelian
    public const PESANAN_PEMBELIAN = 'Pesanan Pembelian';
    public const PESANAN_PEMBELIAN_IMPOR = 'Pesanan Pembelian Impor';

    public const PENERIMAAN_PEMBELIAN = 'Penerimaan Pembelian';
    public const PENERIMAAN_PEMBELIAN_IMPOR = 'Penerimaan Pembelian Impor';

    public const FAKTUR_PEMBELIAN_VIA_PENERIMAAN = 'Faktur Pembelian Kredit Via Penerimaan';
    public const FAKTUR_PEMBELIAN_VIA_PO = 'Faktur Pembelian Kredit Via PO';
    public const FAKTUR_PEMBELIAN_KREDIT = 'Faktur Pembelian Kredit';
    public const FAKTUR_PEMBELIAN_LUNAS = 'Faktur Pembelian Lunas';
    public const FAKTUR_PEMBELIAN_IMPOR_VIA_PO = 'Faktur Pembelian Impor Via PO';
    public const FAKTUR_PEMBELIAN_IMPOR = 'Faktur Pembelian Impor';
    public const FAKTUR_PEMBELIAN_FORWARDER = 'Faktur Pembelian Forwarder';

    public const RETUR_PEMBELIAN = 'Retur Pembelian';

    // Penjualan
    public const PESANAN_PENJUALAN = 'Pesanan Penjualan';

    public const CETAK_PESANAN_PENJUALAN = 'Cetak Pesanan Penjualan';

    public const SURAT_JALAN = 'Surat Jalan';

    public const FAKTUR_PENJUALAN_VIA_SJ = 'Faktur Penjualan Kredit Via SJ';
    public const FAKTUR_PENJUALAN_VIA_SO = 'Faktur Penjualan Kredit Via SO';
    public const FAKTUR_PENJUALAN_KREDIT = 'Faktur Penjualan Kredit';
    public const FAKTUR_PENJUALAN_LUNAS = 'Faktur Penjualan Lunas';
    public const FAKTUR_PENJUALAN_UMUM = 'Faktur Penjualan Umum';

    public const RETUR_PENJUALAN = 'Retur Penjualan';

    public const PERINTAH_SERVICE_CUSTOMER = 'Perintah Service Customer';
    public const PERINTAH_SERVICE_INTERNAL = 'Perintah Service Internal';

    public const PROSES_SERVICE = 'Proses Service';

    public const PENYELESAIAN_SERVICE = 'Penyelesaian Service';
    // Pos
    public const POS_INVOICE = 'POS Invoice';

    // Keuangan
    public const KAS_BON = 'Kas Bon';
    public const KAS_KELUAR = 'Kas Keluar';
    public const KAS_MASUK = 'Kas Masuk';
    public const TRANSFER_KAS = 'Transfer Kas';

    // Persediaan
    public const TRANSFER_PERSEDIAAN = 'Transfer Persediaan';
    public const PENAMBAHAN_PERSEDIAAN = 'Penambahan Persediaan';
    public const PENGURANGAN_PERSEDIAAN = 'Pengurangan Persediaan';
    public const STOK_OPNAME = 'Stok Opname';

    // Utang
    public const MEMO_UTANG = 'Memo Utang';
    public const PEMBAYARAN_UTANG = 'Pembayaran Utang';

    // Piutang
    public const MEMO_PIUTANG = 'Memo Piutang';
    public const PENERIMAAN_PUTANG = 'Penerimaan Piutang';

    //Aset Tetap
    public const ASET_TETAP = 'Aset Tetap';
    public const PEMBAYARAN_ASET_TETAP = 'Pembayaran Aset Tetap';
    public const DISPOSISI_ASET_TETAP = 'Disposisi Aset Tetap';
}
