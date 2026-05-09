<?php

namespace App\Models;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;

    protected $route_prefix = 'admin.system.plan';
    protected $permission_prefix = 'admin.system.plan';
    protected $fillable = ['kode', 'nama', 'jumlah_cabang', 'jumlah_user', 'harga', 'masa_aktif_hari', 'keterangan', 'status'];

    public function autoNumberPrefix(array $data = [])
    {
        return 'PLAN';
    }
}
