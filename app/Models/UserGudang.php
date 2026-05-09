<?php

namespace App\Models;

use App\Models\Master\Gudang;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserGudang extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'user_id', 'gudang_id',
    ];

    // region Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }
    // endregion
}
