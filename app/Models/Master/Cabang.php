<?php

namespace App\Models\Master;

use App\Casts\AsDateCast;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cabang extends Model implements HasMedia
{
    use HasCoreFeature;
    use InteractsWithMedia;

    protected $route_prefix = 'admin.master.cabang';
    protected $permission_prefix = 'admin.master.cabang';
    protected $fillable = ['kode', 'nama', 'alamat', 'kota', 'telp', 'email', 'perusahaan_id', 'ktp_nama', 'ktp_nomor', 'npwp_nama', 'npwp_nomor', 'sia_nama', 'sia_nomor', 'sia_berlaku', 'sipa_nama', 'sipa_nomor', 'sipa_berlaku', 'is_pkp', 'is_include_ppn', 'ppn_percent', 'keterangan', 'status'];
    protected $casts = [
        'sia_berlaku' => AsDateCast::class,
        'sipa_berlaku' => AsDateCast::class,
    ];

    // region Relationship
    public function perusahaan(): BelongsTo
    {
        return $this->belongsTo(Perusahaan::class);
    }
    // endregion

    // region Permissions
    public function canEdit(): bool
    {
        return false;
    }

    public function canDelete(): bool
    {
        return false;
    }
    // endregion
}
