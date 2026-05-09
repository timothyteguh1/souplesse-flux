<?php

namespace App\Models\Pembelian;

use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Models\Master\Gudang;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Models\Master\Supplier;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use App\Traits\HasMutasiTransaksiAsReference;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturPembelian extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $auto_number_length = 4;
    protected $route_prefix = 'admin.pembelian.retur-pembelian';
    protected $permission_prefix = 'admin.pembelian.retur-pembelian';
    protected $fillable = [
        'cabang_id',
        'kode',
        'tanggal',
        'supplier_id',
        'gudang_id',
        'is_pkp',
        'is_include_ppn',
        'ppn_percent',
        'keterangan',
        'status',
    ];
    protected $attributes = [
        'status' => Const_Status::RETUR_PEMBELIAN_BELUM_LUNAS,
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        return 'RPB/' . $tanggal . '/';
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(ReturPembelianDetail::class, 'retur_pembelian_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }
    // endregion

    // region Accessors
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
                    $diskonSatuanPersen = $this->diskon * 100 / $this->total;
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

    public function ppn(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('details');
                $ppn = collect($this->details)->sum('ppn');

                return floor($ppn);
            },
        );
    }

    public function dpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('details');
                $dpp = $this->details->sum('dpp');

                return floor($dpp);
            },
        );
    }

    public function grandtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $grandtotal = $this->total - $this->diskon_rupiah + $this->beban_lain + $this->ppn;
                if ($this->is_pkp && $this->is_include_ppn) {
                    $grandtotal = $this->ppn + $this->dpp;
                }

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
    // endregion
}
