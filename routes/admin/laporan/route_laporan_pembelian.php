<?php

use Illuminate\Support\Facades\Route;

// admin.laporan
Route::group([
    'prefix' => 'laporan/pembelian',
    'as' => 'laporan.pembelian.',
], function () {
    // admin.laporan.pembelian.faktur-pembelian
    Route::get('faktur-pembelian', \App\Livewire\Admin\Laporan\Pembelian\FakturPembelian\Index::class)->name('faktur-pembelian');

    // admin.laporan.pembelian.history-pembelian-produk
    Route::get('history-pembelian-produk', \App\Livewire\Admin\Laporan\Pembelian\HistoryPembelianProduk\Index::class)->name('history-pembelian-produk');
});
