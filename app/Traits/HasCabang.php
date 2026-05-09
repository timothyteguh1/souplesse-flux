<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Master\Cabang;

trait HasCabang
{
    protected $isCabang = true;

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }
}
