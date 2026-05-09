<?php

use Illuminate\Support\Facades\Route;

// admin.persediaan
Route::group([
    'prefix' => 'persediaan',
    'as' => 'persediaan.',
], function () {
    // admin.persediaan.persediaan
    Route::group([
        'prefix' => 'persediaan',
        'as' => 'persediaan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Persediaan\Persediaan\Index::class)->name('index');
    });

    // admin.persediaan.transfer-persediaan
    Route::group([
        'prefix' => 'transfer-persediaan',
        'as' => 'transfer-persediaan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Persediaan\TransferPersediaan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Persediaan\TransferPersediaan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Persediaan\TransferPersediaan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Persediaan\TransferPersediaan\Edit::class)->name('edit');
    });

    // admin.persediaan.penambahan-persediaan
    Route::group([
        'prefix' => 'penambahan-persediaan',
        'as' => 'penambahan-persediaan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Persediaan\PenambahanPersediaan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Persediaan\PenambahanPersediaan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Persediaan\PenambahanPersediaan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Persediaan\PenambahanPersediaan\Edit::class)->name('edit');
    });

    // admin.persediaan.pengurangan-persediaan
    Route::group([
        'prefix' => 'pengurangan-persediaan',
        'as' => 'pengurangan-persediaan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Persediaan\PenguranganPersediaan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Persediaan\PenguranganPersediaan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Persediaan\PenguranganPersediaan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Persediaan\PenguranganPersediaan\Edit::class)->name('edit');
    });

    // admin.persediaan.stok-opname
    Route::group([
        'prefix' => 'stok-opname',
        'as' => 'stok-opname.',
    ], function () {
        Route::get('', \App\Livewire\Admin\Persediaan\StokOpname\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\Persediaan\StokOpname\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\Persediaan\StokOpname\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\Persediaan\StokOpname\Edit::class)->name('edit');
        Route::get('add-multiple/{obj}', \App\Livewire\Admin\Persediaan\StokOpname\AddMultiple::class)->name('add-multiple');
    });
});
