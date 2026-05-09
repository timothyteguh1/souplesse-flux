<?php

namespace App\Models\Penjualan;

use App\Models\Master\Beban;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakturPenjualanBeban extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'id',
        'faktur_penjualan_id',
        'beban_id',
        'jumlah',
        'is_alokasikan_ke_produk',
        'pesanan_penjualan_beban_id',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->fakturPenjualan();
    }

    public function fakturPenjualan(): BelongsTo
    {
        return $this->belongsTo(FakturPenjualan::class);
    }

    public function beban(): BelongsTo
    {
        return $this->belongsTo(Beban::class);
    }

    public function pesananPenjualanBeban(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualanBeban::class);
    }
    // endregion
}
