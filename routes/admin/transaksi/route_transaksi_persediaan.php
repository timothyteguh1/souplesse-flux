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
});
