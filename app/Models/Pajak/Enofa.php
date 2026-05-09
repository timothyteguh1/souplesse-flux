<?php

namespace App\Models\Pajak;

use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Enofa extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;

    protected $route_prefix = 'admin.pajak.enofa';
    protected $permission_prefix = 'admin.pajak.enofa';
    protected $fillable = [
        'cabang_id',
        'kode',
        'tanggal',
        'tanggal_berlaku',
        'nomor_awal',
        'nomor_akhir',
        'keterangan',
        'status',
    ];
    protected $attributes = [
        'status' => Const_Status::AKTIF,
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
        'tanggal_berlaku' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ym');
        }

        return 'NOFA/' . $tanggal . '/';
    }

    public function autoNumberLength()
    {
        return 2;
    }

    // region Relationships
    public function nsfps()
    {
        return $this->hasMany(Nsfp::class);
    }
    // endregion

    // region Attributes
    public function nsfpNomorAwalKodeFormat(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->nomor_awal) {
                    return;
                }

                $nsfp = $this->nomor_awal;
                $nsfp = substr_replace($nsfp, "-", 3, 0);
                $nsfp = substr_replace($nsfp, ".", 6, 0);
                return $nsfp;
            },
        );
    }

    public function nsfpNomorAkhirKodeFormat(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->nomor_akhir) {
                    return;
                }

                $nsfp = $this->nomor_akhir;
                $nsfp = substr_replace($nsfp, "-", 3, 0);
                $nsfp = substr_replace($nsfp, ".", 6, 0);
                return $nsfp;
            },
        );
    }
    // endregion
}
