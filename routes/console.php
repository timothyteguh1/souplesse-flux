<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('heartbeat', function () {
    Setting::put('heartbeat', now());
})->purpose('Updates heartbeat setting with current time')->everyMinute();

Schedule::command('backup:run --only-db --disable-notifications')->daily();

Schedule::command('backup:clean')->daily();
