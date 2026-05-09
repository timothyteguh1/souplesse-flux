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

class FakturPenjualanDetail extends Model
{
    use HasCoreFeature;
    use HasMutasiStok;

    protected $fillable = [
        'faktur_penjualan_id',
        'pesanan_penjualan_id',
        'pesanan_penjualan_detail_id',
        'surat_jalan_detail_id',
        'produk_id',
        'satuan_id',
        'jumlah',
        'harga_satuan',
        'diskon_satuan_type_1',
        'diskon_satuan_1',
        'diskon_satuan_type_2',
        'diskon_satuan_2',
        'diskon_satuan_type_3',
        'diskon_satuan_3',
        'diskon_satuan_type_4',
        'diskon_satuan_4',
        'is_promo_grosir_applied',
        'keterangan',
    ];

    // region Relationships
    public function header(): BelongsTo
    {
        return $this->fakturPenjualan();
    }

    public function fakturPenjualan(): BelongsTo
    {
        return $this->belongsTo(FakturPenjualan::class);
    }

    public function pesananPenjualan(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualan::class);
    }

    public function pesananPenjualanDetail(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualanDetail::class);
    }

    public function suratJalanDetail(): BelongsTo
    {
        return $this->belongsTo(SuratJalanDetail::class);
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

    public function diskon(): Attribute
    {
        return Attribute::make(
            get: function () {
                $diskon_satuan_persen_1 = 0;
                $diskon_satuan_rupiah_1 = 0;

                if ($this->diskon_satuan_1 > 0) {
                    if ($this->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_RP) {
                        $diskon_satuan_rupiah_1 = $this->diskon_satuan_1;
                        $diskon_satuan_persen_1 = $this->harga_satuan != 0 ? $diskon_satuan_rupiah_1 * 100 / $this->harga_satuan : 0;
                    }
                    if ($this->diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_PERCENT) {
                        $diskon_satuan_persen_1 = $this->diskon_satuan_1;
                        $diskon_satuan_rupiah_1 = $this->harga_satuan * $diskon_satuan_persen_1 / 100;
                    }
                }

                $harga_setelah_diskon_1 = $this->harga_satuan - $diskon_satuan_rupiah_1;
                $diskon_satuan_persen_2 = 0;
                $diskon_satuan_rupiah_2 = 0;

                if ($this->diskon_satuan_2 > 0) {
                    if ($this->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_RP) {
                        $diskon_satuan_rupiah_2 = $this->diskon_satuan_2;
                        $diskon_satuan_persen_2 = $harga_setelah_diskon_1 != 0 ? $diskon_satuan_rupiah_2 * 100 / $harga_setelah_diskon_1 : 0;
                    }
                    if ($this->diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_PERCENT) {
                        $diskon_satuan_persen_2 = $this->diskon_satuan_2;
                        $diskon_satuan_rupiah_2 = $harga_setelah_diskon_1 * $diskon_satuan_persen_2 / 100;
                    }
                }
                $harga_setelah_diskon_2 = $harga_setelah_diskon_1 - $diskon_satuan_rupiah_2;
                $diskon_satuan_persen_3 = 0;
                $diskon_satuan_rupiah_3 = 0;

                if ($this->diskon_satuan_3 > 0) {
                    if ($this->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_RP) {
                        $diskon_satuan_rupiah_3 = $this->diskon_satuan_3;
                        $diskon_satuan_persen_3 = $harga_setelah_diskon_2 != 0 ? $diskon_satuan_rupiah_3 * 100 / $harga_setelah_diskon_2 : 0;
                    }
                    if ($this->diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_PERCENT) {
                        $diskon_satuan_persen_3 = $this->diskon_satuan_3;
                        $diskon_satuan_rupiah_3 = $harga_setelah_diskon_2 * $diskon_satuan_persen_3 / 100;
                    }
                }

                $harga_setelah_diskon_3 = $harga_setelah_diskon_2 - $diskon_satuan_rupiah_3;
                $diskon_satuan_persen_4 = 0;
                $diskon_satuan_rupiah_4 = 0;

                if ($this->diskon_satuan_4 > 0) {
                    if ($this->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_RP) {
                        $diskon_satuan_rupiah_4 = $this->diskon_satuan_4;
                        $diskon_satuan_persen_4 = $harga_setelah_diskon_3 != 0 ? $diskon_satuan_rupiah_4 * 100 / $harga_setelah_diskon_3 : 0;
                    }
                    if ($this->diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_PERCENT) {
                        $diskon_satuan_persen_4 = $this->diskon_satuan_4;
                        $diskon_satuan_rupiah_4 = $harga_setelah_diskon_3 * $diskon_satuan_persen_4 / 100;
                    }
                }
                $harga_net_satuan = $harga_setelah_diskon_3 - $diskon_satuan_rupiah_4;

                $diskon_satuan_rupiah = ($this->harga_satuan - $harga_net_satuan);
                $diskon_satuan_persen = $this->harga_satuan == 0 ? 0 : ($diskon_satuan_rupiah * 100) / $this->harga_satuan;

                return [
                    'diskon_satuan_rupiah' => _round($diskon_satuan_rupiah),
                    'diskon_satuan_persen' => _round($diskon_satuan_persen),
                ];
            },
        );
    }

    public function diskonSatuanPersen(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->diskon['diskon_satuan_persen'];
            },
        );
    }

    public function diskonSatuanRupiah(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->diskon['diskon_satuan_rupiah'];
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
