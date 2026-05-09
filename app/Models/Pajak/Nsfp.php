<?php

namespace App\Models\Pajak;

use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Models\Penjualan\FakturPenjualan;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Nsfp extends Model
{
    use HasCabang;
    use HasCoreFeature;

    protected $route_prefix = 'admin.pajak.nsfp';
    protected $permission_prefix = 'admin.pajak.nsfp';
    protected $fillable = [
        'enofa_id', 'kode', 'keterangan', 'status',
    ];
    protected $attributes = [
        'status' => Const_Status::AKTIF,
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
    ];

    // region Relationships
    public function enofa(): BelongsTo
    {
        return $this->belongsTo(Enofa::class);
    }

    public function fakturPenjualan(): HasOne
    {
        return $this->hasOne(FakturPenjualan::class);
    }
    // endregion

    // region Attributes
    public function nsfpKodeFormat(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->kode) {
                    return;
                }

                $nsfp = $this->kode;
                $nsfp = substr_replace($nsfp, "-", 3, 0);
                $nsfp = substr_replace($nsfp, ".", 6, 0);
                return $nsfp;
            },
        );
    }
    // endregion
}
