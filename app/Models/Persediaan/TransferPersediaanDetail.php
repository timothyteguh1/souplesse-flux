<?php

namespace App\Models\Persediaan;

use App\Casts\AsDateCast;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiStok;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransferPersediaanDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'transfer_persediaan_id', 'produk_id', 'satuan_id', 'expired_date', 'no_batch', 'jumlah', 'keterangan',
    ];
    protected $casts = [
        'expired_date' => AsDateCast::class,
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->transferPersediaan();
    }

    public function transferPersediaan(): BelongsTo
    {
        return $this->belongsTo(TransferPersediaan::class);
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
}
