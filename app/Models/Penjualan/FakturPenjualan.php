<?php

namespace App\Models\Penjualan;

use Carbon\Carbon;
use App\Traits\HasCabang;
use App\Models\Pajak\Nsfp;
use App\Casts\AsDateTimeCast;
use App\Models\Master\Gudang;
use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use App\Models\Master\Customer;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Database\Eloquent\Model;
use App\Utilities\Constants\Const_Status;
use App\Traits\HasMutasiTransaksiAsReference;
use App\Utilities\Functions\TransactionFunction;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\Piutang\PenerimaanPiutangDetail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakturPenjualan extends Model
{
    use HasCabang;
    use HasAutoNumber;
    use HasCoreFeature;
    use HasMutasiTransaksiAsReference;

    protected $route_prefix = 'admin.penjualan.faktur-penjualan';
    protected $permission_prefix = 'admin.penjualan.faktur-penjualan';
    protected $fillable = [
        'cabang_id',
        'kode',
        'jenis_transaksi',
        'pesanan_penjualan_id',
        'surat_jalan_id',
        'tanggal',
        'tanggal_jatuh_tempo',
        'customer_id',
        'gudang_id',
        'is_pkp',
        'is_include_ppn',
        'ppn_percent',
        'diskon_type',
        'diskon',
        'batal_alasan',
        'batal_user_id',
        'batal_at',
        'nsfp_id',
        'tanggal_faktur_pajak',
        'bukti_potong',
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
        'tanggal_jatuh_tempo' => AsDateTimeCast::class,
        'batal_at' => AsDateTimeCast::class,
        'tanggal_faktur_pajak' => AsDateTimeCast::class,
    ];

    public function autoNumberPrefix(array $data = [])
    {
        $tanggal = date('ymd');
        if (isset($this->tanggal)) {
            $date = _datetime_carbon_db($this->tanggal);
            $tanggal = $date->format('ymd');
        }

        if ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT || $this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            $prefix = 'FPJ';
        } elseif ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ) {
            $prefix = 'FPJSJ';
        } elseif ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SO) {
            $prefix = 'FPJSO';
        }

        return $prefix . '/' . $tanggal . '/';
    }

    public function getRoutePrefix()
    {
        if ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT || $this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            return 'admin.penjualan.faktur-penjualan';
        } elseif ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ) {
            return 'admin.penjualan.faktur-penjualan-via-sj';
        } elseif ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SO) {
            return 'admin.penjualan.faktur-penjualan-via-so';
        }

        return $this->getFromCurrentRoute();
    }

    public function getPermissionPrefix()
    {
        if ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_KREDIT || $this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_LUNAS) {
            return 'admin.penjualan.faktur-penjualan';
        } elseif ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ) {
            return 'admin.penjualan.faktur-penjualan-via-sj';
        } elseif ($this->jenis_transaksi == Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SO) {
            return 'admin.penjualan.faktur-penjualan-via-so';
        }

        return $this->getFromCurrentRoute();
    }

    // region Relationships
    public function details(): HasMany
    {
        return $this->hasMany(FakturPenjualanDetail::class);
    }

    public function fakturPenjualanBebans(): HasMany
    {
        return $this->hasMany(FakturPenjualanBeban::class, 'faktur_penjualan_id');
    }

    public function pembayarans(): HasMany
    {
        return $this->hasMany(FakturPenjualanPembayaran::class, 'faktur_penjualan_id');
    }

    public function pesananPenjualan(): BelongsTo
    {
        return $this->belongsTo(PesananPenjualan::class);
    }

    public function suratJalan(): BelongsTo
    {
        return $this->belongsTo(SuratJalan::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }

    public function nsfp()
    {
        return $this->belongsTo(Nsfp::class);
    }
    // endregion

    // region Attributes
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
                $this->loadMissing('fakturPenjualanBebans');

                foreach ($this->fakturPenjualanBebans as $detail) {
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

    public function hpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('details');
                $total = $this->details->sum('hpp');

                return _round($total);
            },
        );
    }

    public function tanggalLunas(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->status == Const_Status::FAKTUR_PENJUALAN_BELUM_LUNAS) {
                    return;
                }

                $penerimaanPiutangDetail = PenerimaanPiutangDetail::where('mutasi_transaksi_id', $this->mutasiTransaksi?->id)->get();
                $terbayar = $penerimaanPiutangDetail->sum('nominal');
                $grandtotal = $this->grandtotal;
                if ($terbayar == $grandtotal) {
                    $pembayaranTerakhir = $penerimaanPiutangDetail->loadMissing('header')->last();
                    $tanggal_lunas = $pembayaranTerakhir?->header?->tanggal;
                    return $tanggal_lunas;
                }

                return;
            },
        );
    }
    // endregion

    // region function
    public function getTerbayar(?Carbon $tanggal = null)
    {
        $this->loadMissing('mutasiTransaksi');
        $terbayar = TransactionFunction::getSisaPiutang($this->mutasiTransaksi?->id, $tanggal);
        return _round($terbayar);
    }

    public function getSisaTagihan(?Carbon $tanggal = null)
    {
        $terbayar = $this->getTerbayar($tanggal);
        $sisaTagihan = $this->grandtotal - $terbayar;

        return _round($sisaTagihan);
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
            return auth()->user()->can($this->getPermissionEdit()) && $this->status != Const_Status::FAKTUR_PENJUALAN_LUNAS;
        }
        return $this->status != Const_Status::FAKTUR_PENJUALAN_LUNAS;
    }

    public function canDelete(): bool
    {
        if (auth()->user()) {
            return auth()->user()->can($this->getPermissionDelete()) && $this->status != Const_Status::FAKTUR_PENJUALAN_LUNAS;
        }
        return $this->status != Const_Status::FAKTUR_PENJUALAN_LUNAS;
    }
    // endregion
}