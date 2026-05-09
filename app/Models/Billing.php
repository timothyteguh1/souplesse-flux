<?php

namespace App\Models;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Casts\AsDateTimeCast;
use App\Models\Master\Perusahaan;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Billing extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;

    protected $route_prefix = 'admin.system.billing';
    protected $permission_prefix = 'admin.system.billing';
    protected $fillable = [
        'kode',
        'tanggal',
        'tanggal_jatuh_tempo',
        'perusahaan_id',
        'is_pkp',
        'is_include_ppn',
        'ppn_percent',
        'diskon_type',
        'diskon',
        'beban_lain',
        'keterangan',
        'status',
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'BILL';
    }

    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
        'tanggal_jatuh_tempo' => AsDateTimeCast::class,
    ];

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(BillingDetail::class);
    }

    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
    // endregion
}
