<?php

namespace App\Models\Penjualan;

use App\Casts\AsDateCast;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Traits\HasCoreFeature;
use App\Traits\HasMutasiStok;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratJalanDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'id',
        'surat_jalan_id',
        'pesanan_penjualan_detail_id',
        'produk_id',
        'satuan_id',
        // 'expired_date',
        'jumlah',
        'keterangan',
    ];

    // protected $casts = [
    //     'expired_date' => AsDateCast::class,
    // ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->suratJalan();
    }

    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class);
    }

    public function pesananPenjualanDetail(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualanDetail::class);
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
    // endregion

    // region Accessors
    public function diskonSatuanPersen(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->pesananPenjualanDetail->diskon_satuan_type == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskonSatuanPersen = $this->pesananPenjualanDetail->diskon_satuan;
                } else {
                    $diskonSatuanPersen = $this->pesananPenjualanDetail->diskon_satuan * 100 / $this->pesananPenjualanDetail->harga_satuan;
                }

                return _round($diskonSatuanPersen);
            },
        );
    }

    public function diskonSatuanRupiah(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->pesananPenjualanDetail->diskon_satuan_type == Const_Umum::DISKON_TYPE_VALUE) {
                    $diskonSatuanRupiah = $this->pesananPenjualanDetail->diskon_satuan;
                } else {
                    $diskonSatuanRupiah = $this->pesananPenjualanDetail->diskon_satuan * $this->pesananPenjualanDetail->harga_satuan / 100;
                }

                return _round($diskonSatuanRupiah);
            },
        );
    }

    public function hargaNetSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $harga_net_satuan = $this->pesananPenjualanDetail->harga_satuan - $this->pesananPenjualanDetail->diskon_satuan_rupiah;

                return _round($harga_net_satuan);
            },
        );
    }

    public function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->pesananPenjualanDetail->harga_net_satuan * $this->pesananPenjualanDetail->jumlah;

                return _round($subtotal);
            },
        );
    }

    public function diskonSatuanFooter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('pesananPenjualanDetail.header');
                $diskonSatuanFooter = $this->pesananPenjualanDetail->dpp_satuan / $this->pesananPenjualanDetail->header->dpp * $this->pesananPenjualanDetail->header->diskon_rupiah;

                return _round($diskonSatuanFooter);
            },
        );
    }

    public function biayaSatuanFooter(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('pesananPenjualanDetail.header');
                $biayaSatuanFooter = $this->pesananPenjualanDetail->dpp_satuan / $this->pesananPenjualanDetail->header->dpp * $this->pesananPenjualanDetail->header->biaya_lain;

                return _round($biayaSatuanFooter);
            },
        );
    }

    public function hargaNetSatuanAkhir(): Attribute
    {
        return Attribute::make(
            get: function () {
                $hargaNetSatuanAkhir = $this->pesananPenjualanDetail->harga_net_satuan - $this->pesananPenjualanDetail->diskon_satuan_footer + $this->pesananPenjualanDetail->biaya_satuan_footer;

                return _round($hargaNetSatuanAkhir);
            },
        );
    }

    public function ppnSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('pesananPenjualanDetail.header');
                $ppn_satuan = $this->pesananPenjualanDetail->dpp_satuan / $this->pesananPenjualanDetail->header->dpp * $this->pesananPenjualanDetail->header->ppn;

                return _round($ppn_satuan);
            },
        );
    }

    public function dppSatuan(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('pesananPenjualanDetail.header');
                $dpp_satuan = $this->pesananPenjualanDetail->header->dpp / $this->pesananPenjualanDetail->header->total * $this->pesananPenjualanDetail->subtotal / $this->jumlah;

                return _round($dpp_satuan);
            },
        );
    }

    public function dpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dpp = $this->pesananPenjualanDetail->dpp_satuan * $this->pesananPenjualanDetail->jumlah;

                return _round($dpp);
            },
        );
    }

    public function ppn(): Attribute
    {
        return Attribute::make(
            get: function () {
                $ppn = $this->pesananPenjualanDetail->ppn_satuan * $this->pesananPenjualanDetail->jumlah;

                return _round($ppn);
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

    public function sisaFaktur(): Attribute
    {
        return Attribute::make(
            get: function () {
                $jumlah = $this->jumlah - $this->jumlah_faktur;
                return $jumlah;
            },
        );
    }

    public function isTerfakturSemua(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->jumlah <= $this->jumlah_faktur;
            },
        );
    }
    // endregion
}
