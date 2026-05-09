<?php

namespace App\Models\Penjualan;

use App\Models\Master\Kas;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiTransaksiAsReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakturPenjualanPembayaran extends Model
{
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $fillable = [
        'faktur_penjualan__id', 'kas_id', 'jumlah', 'keterangan',
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

    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
    // endregion
}
