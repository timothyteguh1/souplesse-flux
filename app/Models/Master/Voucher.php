<?php

namespace App\Models\Master;

use App\Casts\AsDateTimeCast;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCabang;

class Voucher extends Model
{
    use HasCoreFeature;
    use HasCabang;

    protected $route_prefix = 'admin.master.voucher';
    protected $permission_prefix = 'admin.master.voucher';
    protected $fillable = [
        'cabang_id', 'kode', 'nama', 'is_masa_aktif', 'tanggal_awal', 'tanggal_akhir',
        'is_isi_customer', 'is_have_kuota', 'jumlah_kuota', 'diskon_type', 'diskon', 'diskon_maksimal',
        'minimal_belanja', 'is_gabung_promo', 'is_gabung_voucher_lain', 'keterangan', 'status',
    ];
    protected $casts = [
        'tanggal_awal' => AsDateTimeCast::class,
        'tanggal_akhir' => AsDateTimeCast::class,
    ];
}
