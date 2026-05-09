<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class RestoreDatabaseJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $disk;
    public $path;

    public function __construct($disk, $path)
    {
        $this->disk = $disk;
        $this->path = $path;
    }

    public function handle(): void
    {
        Artisan::call('backup:restore
                --disk="' . $this->disk . '"
                --backup="' . $this->path . '"
                --connection="' . config('database.default') . '"
                --reset
                --no-interaction');
    }
}
