<?php

namespace App\Models\Pembelian;

use App\Models\Master\Kas;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiTransaksiAsReference;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakturPembelianPembayaran extends Model
{
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $fillable = [
        'faktur_pembelian_id', 'kas_id', 'jumlah', 'keterangan',
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

    public function kas(): BelongsTo
    {
        return $this->belongsTo(Kas::class);
    }
    // endregion
}
