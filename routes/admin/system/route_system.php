<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\System\Dashboard\Index;
use App\Livewire\Admin\System\Dashboard\Keuangan;
use App\Livewire\Admin\System\Dashboard\Pembelian;
use App\Livewire\Admin\System\Dashboard\Penjualan;
use App\Livewire\Admin\System\Dashboard\Persediaan;

Route::get('', function () {
    return to_route(_get_homepage_route());
});

Route::get('dashboard', Index::class)->name('dashboard');

Route::group([
    'prefix' => 'dashboard',
    'as' => 'dashboard.',
], function () {
    Route::get('pembelian', Pembelian::class)->name('pembelian');
    Route::get('penjualan', Penjualan::class)->name('penjualan');
    Route::get('persediaan', Persediaan::class)->name('persediaan');
    Route::get('keuangan', Keuangan::class)->name('keuangan');
});

Route::get('profile', \App\Livewire\Admin\System\Profile\Index::class)->name('profile');

// admin.system
Route::group([
    'prefix' => 'system',
    'as' => 'system.',
], function () {
    // admin.system.plan
    Route::group([
        'prefix' => 'plan',
        'as' => 'plan.',
    ], function () {
        Route::get('', \App\Livewire\Admin\System\Plan\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\System\Plan\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\System\Plan\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\System\Plan\Edit::class)->name('edit');
    });

    // admin.system.billing
    Route::group([
        'prefix' => 'billing',
        'as' => 'billing.',
    ], function () {
        Route::get('', \App\Livewire\Admin\System\Billing\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\System\Billing\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\System\Billing\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\System\Billing\Edit::class)->name('edit');
    });

    // admin.system.user
    Route::group([
        'prefix' => 'user',
        'as' => 'user.',
    ], function () {
        Route::get('', \App\Livewire\Admin\System\User\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\System\User\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\System\User\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\System\User\Edit::class)->name('edit');
    });

    // admin.system.role
    Route::group([
        'prefix' => 'role',
        'as' => 'role.',
    ], function () {
        Route::get('', \App\Livewire\Admin\System\Role\Index::class)->name('index');
        Route::get('create', \App\Livewire\Admin\System\Role\Create::class)->name('create');
        Route::get('show/{obj}', \App\Livewire\Admin\System\Role\Show::class)->name('show');
        Route::get('edit/{obj}', \App\Livewire\Admin\System\Role\Edit::class)->name('edit');
    });

    // admin.system.mutasi-stok
    Route::group([
        'prefix' => 'mutasi-stok',
        'as' => 'mutasi-stok.',
    ], function () {
        Route::get('', App\Livewire\Admin\System\MutasiStok\Index::class)->name('index');
        Route::get('show/{obj}', App\Livewire\Admin\System\MutasiStok\Show::class)->name('show');
    });

    // admin.system.mutasi-transaksi
    Route::group([
        'prefix' => 'mutasi-transaksi',
        'as' => 'mutasi-transaksi.',
    ], function () {
        Route::get('', App\Livewire\Admin\System\MutasiTransaksi\Index::class)->name('index');
        Route::get('show/{obj}', App\Livewire\Admin\System\MutasiTransaksi\Show::class)->name('show');
    });

    // admin.system.activity-log
    Route::group([
        'prefix' => 'activity-log',
        'as' => 'activity-log.',
    ], function () {
        Route::get('', App\Livewire\Admin\System\ActivityLog\Index::class)->name('index');
        Route::get('show/{obj}', App\Livewire\Admin\System\ActivityLog\Show::class)->name('show');
    });

    // backend.system.setting
    Route::group([
        'prefix' => 'setting',
        'as' => 'setting.',
    ], function () {
        Route::get('perusahaan', \App\Livewire\Admin\System\Setting\Perusahaan::class)->name('perusahaan');
        Route::get('stok-awal', \App\Livewire\Admin\System\Setting\StokAwal::class)->name('stok-awal');
    });

    // admin.system.database
    Route::group([
        'prefix' => 'database',
        'as' => 'database.',
    ], function () {
        Route::get('backup', App\Livewire\Admin\System\Database\Backup::class)->name('backup');
        Route::get('restore', App\Livewire\Admin\System\Database\Restore::class)->name('restore');
    });
        // admin.system.accurate
    Route::group([
        'prefix' => 'accurate',
        'as' => 'accurate.',
    ], function () {
        Route::get('', \App\Livewire\Admin\System\Accurate\Index::class)->name('index');
    });
});
