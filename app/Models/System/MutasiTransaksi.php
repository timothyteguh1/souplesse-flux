<?php

namespace App\Models\System;

use App\Casts\AsDateTimeCast;
use App\Traits\HasCabang;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCanAction;
use App\Traits\HasRoute;

class MutasiTransaksi extends Model
{
    use HasCoreFeature;
    use HasCabang;
    use HasRoute;
    use HasCanAction;

    protected $route_prefix = 'admin.system.mutasi-transaksi';
    protected $permission_prefix = 'admin.system.mutasi-transaksi';
    protected $fillable = [
        'cabang_id', 'tanggal', 'jenis',
        'vendor_id', 'vendor_type',
        'reference_id', 'reference_type',
        'header_id', 'header_type', 'jenis_transaksi',
        'jumlah', 'keterangan', 'status',
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
    ];

    // region Relationships
    public function vendor()
    {
        return $this->morphTo();
    }

    public function reference()
    {
        return $this->morphTo();
    }

    public function header()
    {
        return $this->morphTo();
    }
    // endregion
}
