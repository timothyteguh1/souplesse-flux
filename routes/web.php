<?php

use App\Http\Middleware\CabangMiddleware;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccurateController;

//Route::get('/', function () {
//    return view('welcome');
//});

// --------------------------------------------------------------------------
// ADMIN ROUTES
// Accurate OAuth - letakkan di LUAR group admin (sebelum Route::group admin)
Route::get('/perusahaan/{perusahaan}/accurate/connect', [AccurateController::class, 'connect'])->name('accurate.connect');
Route::get('/accurate/callback', [AccurateController::class, 'callback'])->name('accurate.callback');

Route::group([
    'prefix' => '',
    'as' => 'admin.',
    'middleware' => ['web'],
], function () {
    require base_path('routes/admin/auth/route_guest.php');
    require base_path('routes/admin/auth/route_auth.php');

    Route::impersonate();
});

Route::group([
    'prefix' => '',
    'as' => 'admin.',
    'middleware' => ['web', 'auth', 'auth.session', CabangMiddleware::class],
], function () {
    // Master
    require base_path('routes/admin/master/route_master.php');

    // Transaksi
    require base_path('routes/admin/transaksi/route_transaksi_pembelian.php');
    require base_path('routes/admin/transaksi/route_transaksi_penjualan.php');
    require base_path('routes/admin/transaksi/route_transaksi_persediaan.php');

    // Laporan
    require base_path('routes/admin/laporan/route_laporan_persediaan.php');
    require base_path('routes/admin/laporan/route_laporan_pembelian.php');
    require base_path('routes/admin/laporan/route_laporan_penjualan.php');

    // System
    require base_path('routes/admin/system/route_system.php');
});
