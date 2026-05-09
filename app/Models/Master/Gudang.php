<?php

namespace App\Models\Master;

use App\Traits\HasAutoNumber;
use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCabang;

class Gudang extends Model
{
    use HasAutoNumber;
    use HasCoreFeature;
    use HasCabang;

    protected $route_prefix = 'admin.master.gudang';
    protected $permission_prefix = 'admin.master.gudang';
    protected $fillable = ['cabang_id', 'kode', 'nama',  'keterangan', 'status'];

    public function autoNumberPrefix(array $data = [])
    {
        return 'GUD';
    }

    // region Permissions
    public function canDelete(): bool
    {
        if ($this->kode == 'GUD001') {
            return false;
        }
        return true;
    }
    // endregion
}
