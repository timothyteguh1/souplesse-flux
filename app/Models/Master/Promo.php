<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCabang;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Promo extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $route_prefix = 'admin.master.promo';
    protected $permission_prefix = 'admin.master.promo';
    protected $fillable = [
        'cabang_id',
        'kode',
        'produk_id',
        'jumlah_minimum',
        'tambahan_diskon',
        'keterangan',
        'status',
    ];


    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        return 'TD/' . $tanggal . '/';
    }

    // region Relationship
    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }
    // endregion
}
