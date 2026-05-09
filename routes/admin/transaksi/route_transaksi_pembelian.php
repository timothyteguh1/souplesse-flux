<?php

use Illuminate\Support\Facades\Route;

// admin.pembelian
Route::group([
    'prefix' => 'pembelian',
    'as' => 'pembelian.',
], function () {
    // admin.pembelian.pesanan-pembelian
    Route::group([
        'prefix' => 'pesanan-pembelian',
        'as' => 'pesanan-pembelian.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Pembelian\PesananPembelian\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Pembelian\PesananPembelian\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Pembelian\PesananPembelian\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Pembelian\PesananPembelian\Edit::class)->name('edit');
    });

    // admin.pembelian.faktur-pembelian
    Route::group([
        'prefix' => 'faktur-pembelian',
        'as' => 'faktur-pembelian.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Pembelian\FakturPembelian\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Pembelian\FakturPembelian\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Pembelian\FakturPembelian\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Pembelian\FakturPembelian\Edit::class)->name('edit');
    });

    // admin.pembelian.retur-pembelian
    Route::group([
        'prefix' => 'retur-pembelian',
        'as' => 'retur-pembelian.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Pembelian\ReturPembelian\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Pembelian\ReturPembelian\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Pembelian\ReturPembelian\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Pembelian\ReturPembelian\Edit::class)->name('edit');
    });
});
