<?php

namespace App\Models;

use App\Traits\HasCoreFeature;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingDetail extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'billing_id',
        'item',
        'jumlah',
        'harga_satuan',
        'diskon_satuan_type',
        'diskon_satuan',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->billing();
    }

    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class);
    }
    // endregion

    // region Accessors
    public function diskonSatuanPersen(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->diskon_satuan_type == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskonSatuanPersen = $this->diskon_satuan;
                } else {
                    $diskonSatuanPersen = $this->diskon_satuan * 100 / $this->harga_satuan;
                }

                return _round($diskonSatuanPersen);
            },
        );
    }

    public function diskonSatuanRupiah(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->diskon_satuan_type == Const_Umum::DISKON_TYPE_RP) {
                    $diskonSatuanRupiah = $this->diskon_satuan;
                } else {
                    $diskonSatuanRupiah = $this->diskon_satuan * $this->harga_satuan / 100;
                }

                return _round($diskonSatuanRupiah);
            },
        );
    }

    public function hargaNetSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $harga_net_satuan = $this->harga_satuan - $this->diskon_satuan_rupiah;

                return _round($harga_net_satuan);
            },
        );
    }

    public function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->harga_net_satuan * $this->jumlah;

                return _round($subtotal);
            },
        );
    }

    public function diskonSatuanFooter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $diskonSatuanFooter = ($this->subtotal * $this->header->diskon_rupiah / $this->header->total) / $this->jumlah;

                return _round($diskonSatuanFooter);
            },
        );
    }

    public function bebanSatuanFooter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $bebanSatuanFooter = ($this->subtotal * $this->header->beban_lain / $this->header->total) / $this->jumlah;

                return _round($bebanSatuanFooter);
            },
        );
    }

    public function hargaNetSatuanAkhir(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hargaNetSatuanAkhir = $this->harga_net_satuan - $this->diskon_satuan_footer + $this->beban_satuan_footer;

                return _round($hargaNetSatuanAkhir);
            },
        );
    }
    // endregion
}
