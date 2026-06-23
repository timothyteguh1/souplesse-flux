<?php

namespace App\Models\Pembelian;

use App\Casts\AsDateTimeCast;
use App\Models\Master\Supplier;
use App\Traits\HasAutoNumber;
use App\Traits\HasCabang;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiTransaksiAsReference;
use App\Utilities\Constants\Const_Status;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class PesananPembelian extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $auto_number_length = 4;
    protected $route_prefix = 'admin.pembelian.pesanan-pembelian';
    protected $permission_prefix = 'admin.pembelian.pesanan-pembelian';
    
    protected $fillable = [
        'cabang_id',
        'kode',
        'tanggal',
        'supplier_id',
        'is_pkp',
        'is_include_ppn',
        'ppn_percent',
        'pembulatan_rupiah',
        'diskon_type',
        'diskon',
        'beban_lain',
        'nsfp',
        'keterangan',
        'status',

        // --- KOLOM INTEGRASI ACCURATE ---
        'accurate_id',
        'accurate_no',
        'accurate_synced_at',
        'accurate_sync_error',
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

        return 'PPB/' . $tanggal . '/';
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(PesananPembelianDetail::class, 'pesanan_pembelian_id');
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function fakturPembelianDetails(): HasMany
    {
        return $this->hasMany(FakturPembelianDetail::class);
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

    public function canEdit(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionEdit()) && $this->status == Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN;
        }
        return $this->status == Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN;
    }

    public function canDelete(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete()) && $this->status == Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN;
        }
        return $this->status == Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN;
    }

    public function canKonfirmasi(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete()) && $this->canEdit();
        }
        return true;
    }

    public function canPengiriman(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete())
                && $this->status != Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN
                && $this->status != Const_Status::PESANAN_PEMBELIAN_DALAM_PENGIRIMAN
                && $this->status != Const_Status::PESANAN_PEMBELIAN_SELESAI;
        }
        return true;
    }

    public function canSelesaikan(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete())
                && $this->status != Const_Status::PESANAN_PEMBELIAN_MENUNGGU_PERSETUJUAN
                && $this->status != Const_Status::PESANAN_PEMBELIAN_SELESAI;
        }
        return true;
    }
    // endregion
}