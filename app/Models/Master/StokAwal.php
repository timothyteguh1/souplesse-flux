<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasMutasiStok;
use App\Traits\HasCabang;

class StokAwal extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiStok;

    protected $route_prefix = 'admin.master.stok-awal';
    protected $permission_prefix = 'admin.master.stok-awal';
    protected $fillable = [
        'cabang_id',
        'kode',
        'gudang_id',
        'produk_id',
        'satuan_id',
        'expired_date',
        'no_batch',
        'jumlah',
        'harga_satuan',
        'keterangan',
        'status',
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'SA-';
    }

    // region Relationship
    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
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
