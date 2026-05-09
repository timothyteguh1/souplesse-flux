<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCabang;

class Produk extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $auto_number_length = 4;
    protected $route_prefix = 'admin.master.produk';
    protected $permission_prefix = 'admin.master.produk';
    protected $fillable = [
        'cabang_id',
        'kode',
        'nama',
        'jenis_produk_id',
        'kategori_produk_id',
        'model_produk_id',
        'satuan_id',
        'harga_beli',
        'harga_jual',
        'minimal_order',
        'stok_minimum',
        'deskripsi',
        'keterangan',
        'status',
        'default_satuan_beli_id',
        'default_satuan_jual_id',
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'PRDK' . date('y');
    }

    // region Relationship
    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }

    public function jenisProduk(): BelongsTo
    {
        return $this->belongsTo(JenisProduk::class);
    }

    public function kategoriProduk(): BelongsTo
    {
        return $this->belongsTo(KategoriProduk::class);
    }

    public function modelProduk(): BelongsTo
    {
        return $this->belongsTo(ModelProduk::class);
    }
    // endregion

    // region Permissions
    public function canShowHistory(): bool
    {
        return true;
    }
    // endregion
}
