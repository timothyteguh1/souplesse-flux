<?php

namespace App\Models\System;

use App\Casts\AsDateCast;
use App\Casts\AsDateTimeCast;
use App\Models\Master\Gudang;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Traits\HasCabang;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\HasCanAction;
use App\Traits\HasRoute;

class MutasiStok extends Model
{
    use HasCoreFeature;
    use HasCabang;
    use HasRoute;
    use HasCanAction;

    protected $route_prefix = 'admin.system.mutasi-stok';
    protected $permission_prefix = 'admin.system.mutasi-stok';
    protected $fillable = [
        'cabang_id', 'tanggal', 'gudang_id', 'produk_id',
        'satuan_id', 'expired_date', 'no_batch', 'satuan_transaksi_id',
        'reference_id', 'reference_type',
        'header_id', 'header_type', 'jenis_transaksi',
        'jumlah', 'jumlah_transaksi',
        'harga', 'harga_transaksi', 'keterangan', 'status',
    ];
    protected $casts = [
        'tanggal' => AsDateTimeCast::class,
        'expired_date' => AsDateCast::class,
    ];
    // protected $route_prefix = 'admin.persediaan.persediaan';
    // protected $permission_prefix = 'admin.persediaan.persediaan';

    // region Relationships
    public function reference()
    {
        return $this->morphTo();
    }

    public function header()
    {
        return $this->morphTo();
    }

    public function gudang(): BelongsTo
    {
        return $this->belongsTo(Gudang::class);
    }

    public function produk(): BelongsTo
    {
        return $this->belongsTo(Produk::class);
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Satuan::class);
    }

    public function satuanTransaksi(): BelongsTo
    {
        return $this->belongsTo(Satuan::class, 'satuan_transaksi_id');
    }
    // endregion

    // region Attributes
    protected function subtotal(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotal = $this->jumlah * $this->harga;

                return _round($subtotal);
            },
        );
    }

    protected function subtotalTransaksi(): Attribute
    {
        return Attribute::make(
            get: function () {
                $subtotalTransaksi = $this->jumlah_transaksi * $this->harga_transaksi;

                return _round($subtotalTransaksi);
            },
        );
    }
    // endregion
}
