<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCabang;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoGrosir extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $route_prefix = 'admin.master.promo-grosir';
    protected $permission_prefix = 'admin.master.promo-grosir';
    protected $fillable = [
        'cabang_id',
        'kode',
        'nama',
        'customer_id',
        'supplier_id',
        'diskon',
        'keterangan',
        'status',
    ];
    protected $with = ['customer', 'supplier'];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        return 'PRMG/' . $tanggal . '/';
    }

    // region Relationship
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    // endregion
}
