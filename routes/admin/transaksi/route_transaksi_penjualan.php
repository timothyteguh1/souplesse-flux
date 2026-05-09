<?php

use Illuminate\Support\Facades\Route;

// admin.penjualan
Route::group([
    'prefix' => 'penjualan',
    'as' => 'penjualan.',
], function () {
    // admin.penjualan.pesanan-penjualan
    Route::group([
        'prefix' => 'pesanan-penjualan',
        'as' => 'pesanan-penjualan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\PesananPenjualan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\PesananPenjualan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\PesananPenjualan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\PesananPenjualan\Edit::class)->name('edit');
    });

    // admin.penjualan.surat-jalan
    Route::group([
        'prefix' => 'surat-jalan',
        'as' => 'surat-jalan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\SuratJalan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\SuratJalan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\SuratJalan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\SuratJalan\Edit::class)->name('edit');
    });

    // admin.penjualan.faktur-penjualan-via-sj
    Route::group([
        'prefix' => 'faktur-penjualan-via-sj',
        'as' => 'faktur-penjualan-via-sj.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSj\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSj\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSj\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSj\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSj\Edit::class)->name('edit');
    });

    // admin.penjualan.faktur-penjualan-via-so
    Route::group([
        'prefix' => 'faktur-penjualan-via-so',
        'as' => 'faktur-penjualan-via-so.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSo\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSo\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSo\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSo\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\FakturPenjualanViaSo\Edit::class)->name('edit');
    });

    // admin.penjualan.faktur-penjualan
    Route::group([
        'prefix' => 'faktur-penjualan',
        'as' => 'faktur-penjualan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\FakturPenjualan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\FakturPenjualan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\FakturPenjualan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\FakturPenjualan\Edit::class)->name('edit');
    });

    // admin.penjualan.pengiriman
    Route::group([
        'prefix' => 'pengiriman',
        'as' => 'pengiriman.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\Pengiriman\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\Pengiriman\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\Pengiriman\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\Pengiriman\Edit::class)->name('edit');
    });

    // admin.penjualan.retur-penjualan
    Route::group([
        'prefix' => 'retur-penjualan',
        'as' => 'retur-penjualan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Penjualan\ReturPenjualan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Penjualan\ReturPenjualan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Penjualan\ReturPenjualan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Penjualan\ReturPenjualan\Edit::class)->name('edit');
    });
});
