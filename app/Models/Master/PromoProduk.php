<?php

namespace App\Models\Master;

use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCabang;

class PromoProduk extends Model
{
    use HasCoreFeature;
    use HasCabang;

    protected $fillable = [
        'promo_id',
        'produk_id',
        'keterangan',
    ];

    // region Relationships
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
    // endregion
}
