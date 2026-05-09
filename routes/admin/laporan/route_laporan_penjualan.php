<?php

use Illuminate\Support\Facades\Route;

// admin.laporan
Route::group([
    'prefix' => 'laporan/penjualan',
    'as' => 'laporan.penjualan.',
], function () {
    // admin.laporan.penjualan.penjualan
    Route::get('penjualan', \App\Livewire\Admin\Laporan\Penjualan\Penjualan\Index::class)->name('penjualan');

    // admin.laporan.penjualan.pesanan-penjualan-per-sales
    Route::get('pesanan-penjualan-per-sales', \App\Livewire\Admin\Laporan\Penjualan\PesananPenjualanPerSales\Index::class)->name('pesanan-penjualan-per-sales');

    // admin.laporan.penjualan.history-penjualan-produk
    Route::get('history-penjualan-produk', \App\Livewire\Admin\Laporan\Penjualan\HistoryPenjualanProduk\Index::class)->name('history-penjualan-produk');
});
