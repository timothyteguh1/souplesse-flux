<?php

use Illuminate\Support\Facades\Route;

// admin.master
Route::group([
    'prefix' => 'master',
    'as' => 'master.',
], function () {
    // admin.system.perusahaan
    Route::group([
        'prefix' => 'perusahaan',
        'as' => 'perusahaan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Perusahaan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Perusahaan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Perusahaan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Perusahaan\Edit::class)->name('edit');
    });

    // admin.master.cabang
    Route::group([
        'prefix' => 'cabang',
        'as' => 'cabang.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Cabang\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Cabang\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Cabang\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Cabang\Edit::class)->name('edit');
    });

    // admin.master.supplier
    Route::group([
        'prefix' => 'supplier',
        'as' => 'supplier.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Supplier\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Supplier\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\Supplier\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Supplier\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Supplier\Edit::class)->name('edit');
    });

    // admin.master.customer
    Route::group([
        'prefix' => 'customer',
        'as' => 'customer.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Customer\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Customer\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\Customer\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Customer\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Customer\Edit::class)->name('edit');
    });

    // admin.master.karyawan
    Route::group([
        'prefix' => 'karyawan',
        'as' => 'karyawan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Karyawan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Karyawan\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\Karyawan\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Karyawan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Karyawan\Edit::class)->name('edit');
    });

    // admin.master.kategori-produk
    Route::group([
        'prefix' => 'kategori-produk',
        'as' => 'kategori-produk.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\KategoriProduk\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\KategoriProduk\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\KategoriProduk\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\KategoriProduk\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\KategoriProduk\Edit::class)->name('edit');
    });

    // admin.master.jenis-produk
    Route::group([
        'prefix' => 'jenis-produk',
        'as' => 'jenis-produk.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\JenisProduk\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\JenisProduk\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\JenisProduk\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\JenisProduk\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\JenisProduk\Edit::class)->name('edit');
    });

    // admin.master.model-produk
    Route::group([
        'prefix' => 'model-produk',
        'as' => 'model-produk.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\ModelProduk\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\ModelProduk\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\ModelProduk\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\ModelProduk\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\ModelProduk\Edit::class)->name('edit');
    });

    // admin.master.satuan
    Route::group([
        'prefix' => 'satuan',
        'as' => 'satuan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Satuan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Satuan\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\Satuan\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Satuan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Satuan\Edit::class)->name('edit');
    });

    // admin.master.produk
    Route::group([
        'prefix' => 'produk',
        'as' => 'produk.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Produk\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Produk\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\Produk\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Produk\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Produk\Edit::class)->name('edit');
    });

    // admin.master.gudang
    Route::group([
        'prefix' => 'gudang',
        'as' => 'gudang.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Gudang\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Gudang\Create::class)->name('create');
        Route::get('import', \App\Livewire\Admin\Master\Gudang\Import::class)->name('import');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Gudang\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Gudang\Edit::class)->name('edit');
    });

    // admin.master.promo
    Route::group([
        'prefix' => 'promo',
        'as' => 'promo.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Master\Promo\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Master\Promo\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Master\Promo\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Master\Promo\Edit::class)->name('edit');
    });
});
