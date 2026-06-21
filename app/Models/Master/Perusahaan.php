<?php

namespace App\Models\Master;

use App\Models\Plan;
use App\Models\User;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Perusahaan extends Model implements HasMedia
{
    use HasAutoNumber;
    use HasCoreFeature;
    use InteractsWithMedia;

    protected $route_prefix = 'admin.master.perusahaan';
    protected $permission_prefix = 'admin.master.perusahaan';

    protected $fillable = [
        'kode', 'nama',
        'alamat', 'provinsi', 'kota', 'kode_pos',
        'telp', 'fax', 'email', 'user_id', 'plan_id',
        'keterangan', 'status',
        
        // Accurate OAuth & config
        'accurate_access_token',
        'accurate_refresh_token',
        'accurate_token_expires_at',
        'accurate_db_id',
        'accurate_db_alias',
        'accurate_host',
        'accurate_sync_customer',
        'accurate_sync_produk',
        'accurate_sync_faktur',
        'accurate_last_sync_at',
    ];

    protected $casts = [
        'accurate_token_expires_at' => 'datetime',
        'accurate_last_sync_at'     => 'datetime',
        'accurate_sync_customer'    => 'boolean',
        'accurate_sync_produk'      => 'boolean',
        'accurate_sync_faktur'      => 'boolean',
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'PRS-';
    }

    // region Relationship
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }
    // endregion
}