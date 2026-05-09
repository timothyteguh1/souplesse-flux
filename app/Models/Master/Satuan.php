<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCabang;

class Satuan extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $route_prefix = 'admin.master.satuan';
    protected $permission_prefix = 'admin.master.satuan';
    protected $fillable = ['cabang_id', 'kode', 'nama', 'keterangan', 'status'];

    public function autoNumberPrefix(array $data = [])
    {
        return 'SAT';
    }
}
