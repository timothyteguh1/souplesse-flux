<?php

namespace App\Models\Master;

use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCabang;

class PromoCustomer extends Model
{
    use HasCoreFeature;
    use HasCabang;

    protected $fillable = [
        'promo_id',
        'customer_id',
        'keterangan',
    ];

    // region Relationships
    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
    // endregion
}
