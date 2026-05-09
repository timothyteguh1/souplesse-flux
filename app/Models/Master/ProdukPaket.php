<?php

namespace App\Models\Master;

use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProdukPaket extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'id',
        'produk_id',
        'produk_paket_id',
        'jumlah',
        'satuan_id',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->produk();
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function produkPaket(): BelongsTo
    {
        return $this->belongsTo(Produk::class, 'produk_paket_id');
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }
    // endregion
}
