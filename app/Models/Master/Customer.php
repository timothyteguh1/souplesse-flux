<?php

namespace App\Models\Master;

use App\Models\Penjualan\FakturPenjualan;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiTransaksiAsVendor;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCabang;

class Customer extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsVendor;
    use HasCabang;

    protected $auto_number_length = 4;
    protected $route_prefix = 'admin.master.customer';
    protected $permission_prefix = 'admin.master.customer';
    protected $fillable = [
        'cabang_id',
        'kode',
        'nama',

        'telp',
        'handphone',
        'email',
        'alamat',
        'kota',

        'is_blacklist',
        'is_pkp',
        'is_include_ppn',

        'npwp_kode',
        'npwp_nik',
        'npwp_wajib_pajak',
        'npwp_blok',
        'npwp_nomor',
        'npwp_alamat',
        'npwp_kota',
        'npwp_kode_pos',
        'npwp_provinsi',
        'npwp_negara',

        'jatuh_tempo',
        'limit_piutang',
        'rekening_bank',
        'rekening_nomor',
        'rekening_nama',

        'keterangan',
        'status',
        'accurate_id',
        'accurate_no',
        'accurate_synced_at',
    ];

    public function autoNumberPrefix(array $data = [])
    {
        return 'CUS';
    }

    // region Relationships
    public function customerDiskons(): HasMany
    {
        return $this->hasMany(CustomerDiskon::class);
    }

    public function fakturPenjualan(): HasMany
    {
        return $this->hasMany(FakturPenjualan::class);
    }
    // endregion

    // region Attributes
    public function npwpKodeFormat(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->npwp_kode) {
                    return;
                }

                $npwp = $this->npwp_kode;
                $npwp = substr_replace($npwp, '.', 2, 0);
                $npwp = substr_replace($npwp, '.', 6, 0);
                $npwp = substr_replace($npwp, '.', 10, 0);
                $npwp = substr_replace($npwp, '-', 12, 0);
                $npwp = substr_replace($npwp, '.', 16, 0);

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
