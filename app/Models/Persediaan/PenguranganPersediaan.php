<?php

namespace App\Models\Persediaan;

use App\Casts\AsDateTimeCast;
use App\Models\Master\Gudang;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCabang;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PenguranganPersediaan extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;

    protected $route_prefix = 'admin.persediaan.pengurangan-persediaan';
    protected $permission_prefix = 'admin.persediaan.pengurangan-persediaan';
    protected $fillable = ['cabang_id', 'kode', 'tanggal', 'gudang_id', 'keterangan', 'status'];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        return 'PNYK/' . $tanggal . '/';
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(PenguranganPersediaanDetail::class, 'pengurangan_persediaan_id');
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }
    // endregion

    // region Accessors
    public function grandtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('details');
                $total = $this->details->sum('subtotal');

                return _round($total);
            },
        );
    }
    // endregion

    // region Permissions
    public function canPrint(): bool
    {
        return $this->status == Const_Status::PENGURANGAN_PERSEDIAAN_AKTIF;
    }
    // endregion
}
