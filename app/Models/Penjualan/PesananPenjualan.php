<?php

namespace App\Models\Penjualan;

use App\Models\Activity;
use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Models\Master\Customer;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use App\Traits\HasMutasiTransaksiAsReference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananPenjualan extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $auto_number_length = 4;
    protected $route_prefix = 'admin.penjualan.pesanan-penjualan';
    protected $permission_prefix = 'admin.penjualan.pesanan-penjualan';
    protected $fillable = [
        'cabang_id',
        'kode',
        'jenis_transaksi',
        'tanggal',
        'customer_id',
        'is_pkp',
        'is_include_ppn',
        'ppn_percent',
        'diskon_type',
        'diskon',
        'batal_alasan',
        'batal_user_id',
        'batal_at',
        'keterangan',
        'status',
    ];
    protected $attributes = [
        'status' => Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI,
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
        'batal_at' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        return 'PPJ/' . $tanggal . '/';
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(PesananPenjualanDetail::class, 'pesanan_penjualan_id');
    }

    public function pesananPenjualanBebans(): HasMany
    {
        return $this->hasMany(PesananPenjualanBeban::class, 'pesanan_penjualan_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function fakturPenjualanDetails(): HasMany
    {
        return $this->hasMany(FakturPenjualanDetail::class);
    }

    public function suratJalan(): HasOne
    {
        return $this->hasOne(SuratJalan::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function latestActivity()
    {
        return $this->morphOne(Activity::class, 'subject')->latestOfMany();
    }
    public function createdBy()
    {
        return $this->morphOne(Activity::class, 'subject')
            ->where('event', 'created');
    }
    // endregion

    // region Accessors
    public function isTerpenuhi(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('details');
                return $this->details->every(fn($detail) => $detail->is_terpenuhi);
            },
        );
    }

    public function total(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = 0;
                $this->loadMissing('details');
                foreach ($this->details as $detail) {
                    $total += $detail->subtotal;
                }

                return _round($total);
            },
        );
    }

    public function diskonPersen(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->diskon_type == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskonSatuanPersen = $this->diskon;
                } else {
                    $diskonSatuanPersen = $this->total == 0 ? 0 : $this->diskon * 100 / $this->total;
                }

                return _round($diskonSatuanPersen);
            },
        );
    }

    public function diskonRupiah(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->diskon_type == Const_Umum::DISKON_TYPE_RP) {
                    $diskonSatuanRupiah = $this->diskon;
                } else {
                    $diskonSatuanRupiah = $this->total * $this->diskon / 100;
                }

                return _round($diskonSatuanRupiah);
            },
        );
    }

    public function totalBeban(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = 0;
                $this->loadMissing('pesananPenjualanBebans');

                foreach ($this->pesananPenjualanBebans as $detail) {
                    $total += $detail->jumlah;
                }

                return _round($total);
            },
        );
    }

    public function ppn(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ppn = 0;
                if ($this->is_pkp) {
                    $ppn = $this->dpp * ($this->ppn_percent / 100);
                }

                return _round($ppn, 0);
            },
        );
    }

    public function dpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dpp = $this->total - $this->diskon_rupiah + $this->biaya_lain;
                if ($this->is_pkp && $this->is_include_ppn) {
                    $dpp = $dpp / (1 + ($this->ppn_percent / 100));
                }

                return _round($dpp, 0);
            },
        );
    }

    public function grandtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $grandtotal = $this->dpp + $this->ppn + $this->totalBeban;

                return _round($grandtotal);
            },
        );
    }
    // endregion

    // region Permissions
    public function canPrint(): bool
    {
        return true;
    }

    public function canEdit(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionEdit()) && $this->status == Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
        }
        return $this->status == Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
    }

    public function canDelete(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete()) && $this->status == Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
        }
        return $this->status == Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
    }

    public function canKonfirmasi(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete()) && $this->canEdit();
        }
        return true;
    }
    // endregion
}
