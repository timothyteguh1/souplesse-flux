<?php

namespace App\Models\Penjualan;

use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Traits\HasMutasiStok;
use App\Traits\HasCoreFeature;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesananPenjualanDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'id',
        'pesanan_penjualan_id',
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
        return $this->pesananPenjualan();
    }

    public function pesananPenjualan(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualan::class);
    }

    public function fakturPenjualanDetails(): HasMany
    {
        return $this->hasMany(FakturPenjualanDetail::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }

    public function suratJalanDetails(): HasMany
    {
        return $this->hasMany(SuratJalanDetail::class);
    }
    // endregion

    // region Accessors
    public function jumlahFakturTanpaSj(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->fakturPenjualanDetails()->whereNull('surat_jalan_detail_id')->sum('jumlah');
                return $jumlah;
            },
        );
    }

    public function jumlahFaktur(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->fakturPenjualanDetails()->sum('jumlah');
                return $jumlah;
            },
        );
    }

    public function jumlahSuratJalan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->suratJalanDetails()->sum('jumlah');
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

    public function sisaSuratJalan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->jumlah - $this->jumlah_surat_jalan;
                return $jumlah;
            },
        );
    }

    public function isTerpenuhiFaktur(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->jumlah <= $this->jumlah_faktur;
            },
        );
    }

    public function isTerpenuhiSuratJalan(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->jumlah <= $this->jumlah_surat_jalan;
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
                    $diskonSatuanPersen = $this->harga_satuan == 0 ? 0 : $this->diskon_satuan * 100 / $this->harga_satuan;
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
                $diskonSatuanFooter = $this->dpp_satuan / $this->header->dpp * $this->header->diskon_rupiah;

                return _round($diskonSatuanFooter);
            },
        );
    }

    public function biayaSatuanFooter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $biayaSatuanFooter = $this->dpp_satuan / $this->header->dpp * $this->header->biaya_lain;

                return _round($biayaSatuanFooter);
            },
        );
    }

    public function hargaNetSatuanAkhir(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hargaNetSatuanAkhir = $this->harga_net_satuan - $this->diskon_satuan_footer + $this->biaya_satuan_footer;

                return _round($hargaNetSatuanAkhir);
            },
        );
    }

    public function ppnSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $ppn_satuan = $this->dpp_satuan / $this->header->dpp * $this->header->ppn;

                return _round($ppn_satuan);
            },
        );
    }

    public function dppSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('header');
                $dpp_satuan = $this->header->dpp / $this->header->total * $this->subtotal / $this->jumlah;

                return _round($dpp_satuan);
            },
        );
    }

    public function dpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dpp = $this->dpp_satuan * $this->jumlah;

                return _round($dpp);
            },
        );
    }

    public function ppn(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ppn = $this->ppn_satuan * $this->jumlah;

                return _round($ppn);
            },
        );
    }
    // endregion
}
