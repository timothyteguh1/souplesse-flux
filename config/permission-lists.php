<?php

return [
    'special_permissions' => [
        'superuser' => 'Super User',
        'admin.persediaan.persediaan' => 'Persediaan',
        'admin.utang.utang' => 'Utang',
        'admin.piutang.piutang' => 'Piutang',
        'admin.pajak.nsfp' => 'Nomor Seri Faktur Pajak',
        'admin.pajak.mapping-faktur-penjualan' => 'Mapping Faktur Penjualan',
        'admin.pajak.mapping-faktur-pembelian' => 'Mapping Faktur Pembelian',
    ],

    'actions' => [
        'index' => 'Akses Menu',
        'create' => 'Tambah',
        'show' => 'Lihat Detail',
        'edit' => 'Ubah',
        'delete' => 'Hapus',
        'activity-log' => 'Activity Log',
    ],

    'permissions' => [
        'Master' => [
            'admin.master.produk' => 'Produk',
            'admin.master.satuan' => 'Satuan',
            'admin.master.kategori-produk' => 'Kategori Produk',
            'admin.master.jenis-produk' => 'Jenis Produk',
            'admin.master.brand-produk' => 'Brand',

            'admin.master.beban' => 'Beban',
            'admin.master.kategori-beban' => 'Kategori Beban',
            'admin.master.kas' => 'Kas',
            'admin.master.pendapatan' => 'Pendapatan',

            'admin.master.area' => 'Area',
            'admin.master.channel-customer' => 'Channel Customer',
            'admin.master.kelas-customer' => 'Kelas Customer',
            'admin.master.customer' => 'Customer',
            'admin.master.supplier' => 'Supplier',
            'admin.master.karyawan' => 'Karyawan',
            'admin.master.gudang' => 'Gudang',

            'admin.master.promo' => 'Promo',
        ],

        'Fitur' => [
            'admin.pembelian.faktur-pembelian' => 'Faktur Pembelian',
            'admin.pembelian.retur-pembelian' => 'Retur Pembelian',

            'admin.penjualan.pesanan-penjualan' => 'Pesanan Penjualan',
            // 'admin.penjualan.cetak-pesanan-penjualan' => 'Cetak Pesanan Penjualan',
            // 'admin.penjualan.surat-jalan' => 'Surat Jalan',
            // 'admin.penjualan.faktur-penjualan-via-sj' => 'Faktur Penjualan Via SJ',
            'admin.penjualan.faktur-penjualan-via-so' => 'Faktur Penjualan Via SO',
            'admin.penjualan.faktur-penjualan' => 'Faktur Penjualan',
            'admin.penjualan.pengiriman' => 'Pengiriman',
            'admin.penjualan.retur-penjualan' => 'Retur Penjualan',

            'admin.persediaan.transfer-persediaan' => 'Transfer Produk',
            'admin.persediaan.penambahan-persediaan' => 'Penyesuaian Tambah',
            'admin.persediaan.pengurangan-persediaan' => 'Penyesuaian Kurang',
            'admin.persediaan.stok-opname' => 'Stok Opname',

            'admin.keuangan.kas-masuk' => 'Kas Masuk',
            'admin.keuangan.kas-keluar' => 'Kas Keluar',
            'admin.keuangan.transfer-kas' => 'Transfer Kas',
            'admin.keuangan.kas-bon' => 'Pemberian Kas Bon',

            'admin.utang.pembayaran-utang' => 'Pembayaran Utang',

            'admin.piutang.penerimaan-piutang' => 'Penerimaan Piutang',

            'admin.pajak.enofa' => 'E-NOFA',

        ],

        'System' => [
            'admin.system.role' => 'Role',
            'admin.system.user' => 'User',
            'admin.system.activity-log' => 'Activity Log',
            'admin.system.setting.perusahaan' => 'Setting Perusahaan',
            'admin.system.database.backup' => 'Backup Database',
            'admin.system.database.restore' => 'Restore Database',
        ],
    ],

    'report_permissions' => [
        'Laporan Keuangan' => [
            'admin.laporan.keuangan.utang' => 'Utang',
            'admin.laporan.keuangan.piutang' => 'Piutang',
            'admin.laporan.keuangan.kas-bon' => 'Kas Bon',
            'admin.laporan.keuangan.kartu-utang' => 'Kartu Utang',
            'admin.laporan.keuangan.kartu-piutang' => 'Kartu Piutang',
            'admin.laporan.keuangan.kartu-kas-bon' => 'Kartu Kas bon',
            'admin.laporan.keuangan.mutasi-kas' => 'Mutasi Kas',
            'admin.laporan.keuangan.laba-rugi' => 'Laba Rugi',
        ],
        'Laporan Persediaan' => [
            'admin.laporan.persediaan.kartu-stok' => 'Kartu Stok',
            'admin.laporan.persediaan.stok-per-tanggal' => 'Stok Per Tanggal',
            'admin.laporan.persediaan.pergerakan-stok' => 'Pergerakan Stok',
            'admin.laporan.persediaan.nilai-persediaan' => 'Nilai Persediaan',
            'admin.laporan.persediaan.mutasi-nilai-persediaan' => 'Mutasi Nilai Persediaan',
        ],
        'Laporan Pembelian' => [
            'admin.laporan.pembelian.faktur-pembelian' => 'Faktur Pembelian',
            'admin.laporan.pembelian.history-pembelian-produk' => 'History Pembelian Produk',
        ],
        'Laporan Penjualan' => [
            'admin.laporan.penjualan.penjualan' => 'Penjualan',
            'admin.laporan.penjualan.history-penjualan-produk' => 'History Penjualan Produk',
        ],
    ],
];
