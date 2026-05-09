<?php

namespace App\Models\Pembelian;

use App\Models\Master\Beban;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakturPembelianBeban extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'id',
        'faktur_pembelian_id',
        'beban_id',
        'jumlah',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->fakturPembelian();
    }

    public function fakturPembelian(): BelongsTo
    {
        return $this->belongsTo(FakturPembelian::class);
    }

    public function beban(): BelongsTo
    {
        return $this->belongsTo(Beban::class);
    }
    // endregion
}
