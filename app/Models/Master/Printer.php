<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCabang;

class Printer extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $route_prefix = 'admin.master.printer';
    protected $permission_prefix = 'admin.master.printer';
    protected $fillable = ['cabang_id', 'kode', 'nama', 'ip', 'port', 'keterangan', 'status'];

    public function autoNumberPrefix(array $data = [])
    {
        return 'PRNT';
    }
}
