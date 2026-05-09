<?php

namespace App\Models;

use App\Models\Master\Kas;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserKas extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'user_id', 'kas_id',
    ];

    // region Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
    // endregion
}
