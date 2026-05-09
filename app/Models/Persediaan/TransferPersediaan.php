<?php

namespace App\Models\Persediaan;

use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransferPersediaan extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;

    protected $route_prefix = 'admin.persediaan.transfer-persediaan';
    protected $permission_prefix = 'admin.persediaan.transfer-persediaan';
    protected $fillable = ['cabang_id', 'kode', 'tanggal', 'gudang_asal_id', 'gudang_tujuan_id', 'cabang_tujuan_id', 'keterangan', 'status'];
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

        return 'TPRD/' . $tanggal . '/';
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(TransferPersediaanDetail::class, 'transfer_persediaan_id');
    }

    public function gudangAsal(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_asal_id');
    }

    public function cabangTujuan(): BelongsTo
    {
        return $this->belongsTo(Cabang::class, 'cabang_tujuan_id');
    }

    public function gudangTujuan(): BelongsTo
    {
        return $this->belongsTo(Gudang::class, 'gudang_tujuan_id');
    }
    // endregion

    // region Permissions
    public function canPrint(): bool
    {
        return $this->status == Const_Status::TRANSFER_PERSEDIAAN_AKTIF;
    }
    // endregion
}
