<?php

namespace App\Models\Master;

use App\Models\Pembelian\FakturPembelian;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiTransaksiAsVendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Traits\HasCabang;

class Supplier extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsVendor;
    use HasCabang;

    protected $route_prefix = 'admin.master.supplier';
    protected $permission_prefix = 'admin.master.supplier';
    protected $fillable = [
        'cabang_id',
        'kode',
        'nama',
        'telp',
        'handphone',
        'email',

        'alamat',
        'kota',

        'is_pkp',
        'is_include_ppn',

        'jatuh_tempo',
        'rekening_bank',
        'rekening_nomor',
        'rekening_nama',
        'npwp',

        'payment_info',
        'keterangan',
        'status',
        
        // --- TAMBAHAN UNTUK ACCURATE ---
        'accurate_id', 
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'SUP';
    }

    // region Relationships
    public function fakturPembelian(): HasMany
    {
        return $this->hasMany(FakturPembelian::class);
    }
    // endregion

    // region Attributes
    public function npwpKodeFormat(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->npwp) {
                    return;
                }

                $npwp = $this->npwp;
                $npwp = substr_replace($npwp, ".", 2, 0);
                $npwp = substr_replace($npwp, ".", 6, 0);
                $npwp = substr_replace($npwp, ".", 10, 0);
                $npwp = substr_replace($npwp, "-", 12, 0);
                $npwp = substr_replace($npwp, ".", 16, 0);
                return $npwp;
            },
        );
    }
    // endregion

    // region Permissions
    public function canShowHistory(): bool
    {
        return true;
    }
    // endregion
}