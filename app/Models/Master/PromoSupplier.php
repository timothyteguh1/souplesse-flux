<?php

namespace App\Models\Master;

use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCabang;

class PromoSupplier extends Model
{
    use HasCoreFeature;
    use HasCabang;

    protected $fillable = [
        'promo_id',
        'supplier_id',
        'keterangan',
    ];

    // region Relationships
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    // endregion
}
