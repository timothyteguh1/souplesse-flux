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

class PenambahanPersediaanDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'penambahan_persediaan_id',  'produk_id',  'satuan_id', 'expired_date', 'no_batch', 'jumlah', 'harga_satuan', 'keterangan',
    ];
    protected $casts = [
        'expired_date' => AsDateCast::class,
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->penambahanPersediaan();
    }

    public function penambahanPersediaan(): BelongsTo
    {
        return $this->belongsTo(PenambahanPersediaan::class);
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
                return $this->jumlah * $this->harga_satuan;
            },
        );
    }
    //endregion
}
