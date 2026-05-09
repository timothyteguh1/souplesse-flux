<?php

namespace App\Models\Master;

use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukSatuan extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'produk_id', 'satuan_id', 'konversi', 'harga_beli', 'harga_jual', 'barcode', 'is_satuan_dasar', 'keterangan',
    ];

    // region Relationships
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }
    // endregion
}
