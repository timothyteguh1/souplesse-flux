<?php

namespace App\Models\Pembelian;

use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiStok;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesananPembelianDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'pesanan_pembelian_id',
        'produk_id',
        'satuan_id',
        'jumlah',
        'harga_satuan',
        'diskon_satuan_type',
        'diskon_satuan',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->pesananPembelian();
    }

    public function pesananPembelian(): BelongsTo
    {
        return $this->belongsTo(PesananPembelian::class);
    }

    public function fakturPembelianDetails(): HasMany
    {
        return $this->hasMany(FakturPembelianDetail::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }
    // endregion

    // region Accessors
    public function jumlahFaktur(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->fakturPembelianDetails()->sum('jumlah');
                return $jumlah;
            },
        );
    }

    public function sisaFaktur(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->jumlah - $this->jumlah_faktur;
                return $jumlah;
            },
        );
    }

    public function isTerpenuhi(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->jumlah <= $this->jumlah_faktur;
            },
        );
    }

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

    public function ppnSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $ppn_satuan = 0;
                if ($this->header->is_pkp) {
                    if ($this->header->is_include_ppn) {
                        $ppn_satuan = _ppn_value($this->harga_net_satuan_akhir, $this->header->ppn_percent, true);
                    } else {
                        $ppn_satuan = _ppn_value($this->harga_net_satuan_akhir, $this->header->ppn_percent);
                    }
                }

                return floor($ppn_satuan);
            },
        );
    }

    public function dppSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $dpp_satuan = 0;
                if ($this->header->is_pkp) {
                    if ($this->header->is_include_ppn) {
                        $ppn_satuan = _ppn_value($this->harga_net_satuan_akhir, $this->header->ppn_percent, true);
                        $dpp_satuan = _round($this->harga_net_satuan_akhir - $ppn_satuan, 2);
                    } else {
                        $dpp_satuan = _round($this->harga_net_satuan_akhir, 2);
                    }
                }

                return floor($dpp_satuan);
            },
        );
    }

    public function dpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dpp = $this->dpp_satuan * $this->jumlah;

                return floor($dpp);
            },
        );
    }

    public function ppn(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ppn = $this->ppn_satuan * $this->jumlah;

                return floor($ppn);
            },
        );
    }
    // endregion
}
