<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCabang;

class JenisProduk extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $auto_number_length = 2;
    protected $route_prefix = 'admin.master.jenis-produk';
    protected $permission_prefix = 'admin.master.jenis-produk';
    protected $fillable = ['cabang_id', 'kode', 'nama', 'keterangan', 'status'];

    public function autoNumberPrefix(array $data = [])
    {
        return 'JNS';
    }

    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }

    // region Permissions
    public function canEdit(): bool
    {
        $jenis_produk_namas = [
            'Produk',
            'Jasa',
            'Sparepart',
        ];

        if (in_array($this->nama, $jenis_produk_namas)) {
            return false;
        }
        return true;
    }

    public function canDelete(): bool
    {
        $jenis_produk_namas = [
            'Produk',
            'Jasa',
            'Sparepart',
        ];

        if (in_array($this->nama, $jenis_produk_namas)) {
            return false;
        }
        return true;
    }
    // endregion
}
