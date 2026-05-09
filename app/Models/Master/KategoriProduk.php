<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCabang;

class KategoriProduk extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $auto_number_length = 2;
    protected $route_prefix = 'admin.master.kategori-produk';
    protected $permission_prefix = 'admin.master.kategori-produk';
    protected $fillable = ['cabang_id', 'kode', 'nama', 'keterangan', 'status'];

    public function autoNumberPrefix(array $data = [])
    {
        return 'KTG';
    }

    public function produks(): HasMany
    {
        return $this->hasMany(Produk::class);
    }
}
