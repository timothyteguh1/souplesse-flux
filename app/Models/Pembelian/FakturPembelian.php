<?php

namespace App\Models\Pembelian;

use Carbon\Carbon;
use App\Traits\HasCabang;
use App\Casts\AsDateTimeCast;
use App\Models\Master\Gudang;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Models\Master\Supplier;
use App\Models\Utang\PembayaranUtangDetail;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasMutasiTransaksiAsReference;
use App\Utilities\Functions\TransactionFunction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Utilities\Constants\Const_Status;

class FakturPembelian extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $auto_number_length = 4;
    protected $route_prefix = 'admin.pembelian.faktur-pembelian';
    protected $permission_prefix = 'admin.pembelian.faktur-pembelian';
    protected $fillable = [
        'cabang_id',
        'kode',
        'kode_faktur_supplier',
        'jenis_transaksi',
        'tanggal',
        'tanggal_jatuh_tempo',
        'supplier_id',
        'gudang_id',
        'is_pkp',
        'is_include_ppn',
        'ppn_percent',
        'diskon_type',
        'diskon',
        'beban_lain',
        'pph',
        'ppn',
        'nsfp',
        'tanggal_faktur_pajak',
        'bukti_potong',
        'keterangan',
        'status',
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
        'tanggal_jatuh_tempo' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        return 'FPB/' . $tanggal . '/';
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(FakturPembelianDetail::class, 'faktur_pembelian_id');
    }

    public function fakturPembelianBebans(): HasMany
    {
        return $this->hasMany(FakturPembelianBeban::class, 'faktur_pembelian_id');
    }

    public function pesananPembelian(): BelongsTo
    {
        return $this->belongsTo(PesananPembelian::class);
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(FakturPembelianPembayaran::class, 'faktur_pembelian_id');
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
                $this->loadMissing('fakturPembelianBebans');

                foreach ($this->fakturPembelianBebans as $detail) {
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
                $ppn += $this->ppn_selisih;

                return _round($ppn, 0);
            },
        );
    }

    public function dpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $dpp = $this->total - $this->diskon_rupiah;
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

    public function nsfpKodeFormat(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!$this->nsfp) {
                    return;
                }

                $nsfp = $this->nsfp;
                $nsfp = substr_replace($nsfp, "-", 3, 0);
                $nsfp = substr_replace($nsfp, ".", 6, 0);
                return $nsfp;
            },
        );
    }

    public function tanggalLunas(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status == Const_Status::FAKTUR_PEMBELIAN_BELUM_LUNAS) {
                    return;
                }

                $pembayaranUtangDetail = PembayaranUtangDetail::where('mutasi_transaksi_id', $this->mutasiTransaksi?->id)->get();
                $dibayar = $pembayaranUtangDetail->sum('nominal');
                $grandtotal = $this->grandtotal;
                if ($dibayar == $grandtotal) {
                    $pembayaranTerakhir = $pembayaranUtangDetail->loadMissing('header')->last();
                    $tanggal_lunas = $pembayaranTerakhir?->header?->tanggal;
                    return $tanggal_lunas;
                }

                return;
            },
        );
    }
    // endregion

    // region Functions
    public function getSisaUtang(?Carbon $tanggal = null)
    {
        $bayar = TransactionFunction::getSisaUtang($this->mutasiTransaksi?->id, $tanggal);
        $sisa_utang = $this->grandtotal - $bayar;

        return _round($sisa_utang);
    }
    // endregion

    // region Permissions
    public function canPrint(): bool
    {
        return true;
    }
    // endregion
}
