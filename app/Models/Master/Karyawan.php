<?php

namespace App\Models\Master;

use App\Models\User;
use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMutasiTransaksiAsVendor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Karyawan extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsVendor;
    use HasCabang;

    protected $route_prefix = 'admin.master.karyawan';
    protected $permission_prefix = 'admin.master.karyawan';
    protected $fillable = [
        'cabang_id',
        'kode',
        'nama',
        'user_id',
        'no_ktp',
        'tanggal_masuk',
        'level',
        'komisi',

        'telp',
        'handphone',
        'email',

        'alamat',
        'kota',

        'keterangan',
        'status',
        
        // --- TAMBAHAN UNTUK ACCURATE ---
        'accurate_id',
    ];
    protected $casts = [
        'tanggal_masuk' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'KARY' . date('ym');
    }

    // region Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    // endregion
}