<?php

namespace App\Models\Persediaan;

use App\Casts\AsDateCast;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiStok;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenguranganPersediaanDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'pengurangan_persediaan_id', 'produk_id',  'satuan_id', 'expired_date', 'no_batch', 'jumlah', 'harga_satuan', 'keterangan',
    ];
    protected $casts = [
        'expired_date' => AsDateCast::class,
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->penguranganPersediaan();
    }

    public function penguranganPersediaan(): BelongsTo
    {
        return $this->belongsTo(PenguranganPersediaan::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }
    // endregion

    // region Accessors
    public function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->jumlah * $this->harga_satuan;

                return _round($subtotal);
            },
        );
    }
    //endregion
}
