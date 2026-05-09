<?php

namespace App\Models;

use App\Models\Master\Cabang;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCabang extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'user_id', 'cabang_id',
    ];

    // region Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cabang(): BelongsTo
    {
        return $this->belongsTo(Cabang::class);
    }
    // endregion
}
