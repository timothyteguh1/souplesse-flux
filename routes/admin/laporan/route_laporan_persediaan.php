<?php

use Illuminate\Support\Facades\Route;

// admin.laporan
Route::group([
    'prefix' => 'laporan/persediaan',
    'as' => 'laporan.persediaan.',
], function () {
    // admin.laporan.persediaan.kartu-stok
    Route::get('kartu-stok', \App\Livewire\Admin\Laporan\Persediaan\KartuStok\Index::class)->name('kartu-stok');

    // admin.laporan.persediaan.stok-per-tanggal
    Route::get('stok-per-tanggal', \App\Livewire\Admin\Laporan\Persediaan\StokPerTanggal\Index::class)->name('stok-per-tanggal');

    // admin.laporan.persediaan.pergerakan-stok
    Route::get('pergerakan-stok', \App\Livewire\Admin\Laporan\Persediaan\PergerakanStok\Index::class)->name('pergerakan-stok');

    // admin.laporan.persediaan.nilai-persediaan
    Route::get('nilai-persediaan', \App\Livewire\Admin\Laporan\Persediaan\NilaiPersediaan\Index::class)->name('nilai-persediaan');

    // admin.laporan.persediaan.mutasi-nilai-persediaan
    Route::get('mutasi-nilai-persediaan', \App\Livewire\Admin\Laporan\Persediaan\MutasiNilaiPersediaan\Index::class)->name('mutasi-nilai-persediaan');
});
