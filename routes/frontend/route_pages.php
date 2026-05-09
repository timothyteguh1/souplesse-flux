<?php

use Illuminate\Support\Facades\Route;

// admin
Route::group([
    'prefix' => '',
    'as' => 'pages.',
], function () {
    Route::get('', \App\Livewire\Frontend\Pages\Homepage::class)->name('homepage');
    Route::get('shop', \App\Livewire\Frontend\Pages\Shop::class)->name('shop');
    Route::get('return-refund-policy', \App\Livewire\Frontend\Pages\ReturnRefundPolicy::class)->name('return-refund-policy');
    Route::get('terms-and-condition', \App\Livewire\Frontend\Pages\TermsAndCondition::class)->name('terms-and-condition');
});
