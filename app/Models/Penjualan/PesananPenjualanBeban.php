<?php

namespace App\Models\Penjualan;

use App\Models\Master\Beban;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananPenjualanBeban extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'id',
        'pesanan_penjualan_id',
        'beban_id',
        'jumlah',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->pesananPenjualan();
    }

    public function pesananPenjualan(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualan::class);
    }

    public function beban(): BelongsTo
    {
        return $this->belongsTo(Beban::class);
    }
    // endregion
}
