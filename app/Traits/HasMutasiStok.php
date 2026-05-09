<?php

namespace App\Traits;

use App\Models\System\MutasiStok;
use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasMutasiStok
{
    public function mutasiStok()
    {
        return $this->morphOne(MutasiStok::class, 'reference');
    }

    public function mutasiStoks()
    {
        return $this->morphMany(MutasiStok::class, 'reference');
    }

    public function hpp(): Attribute
    {
        return Attribute::make(
            get: function () {
                $this->loadMissing('mutasiStoks');
                $hpp = $this->mutasiStoks->sum(function ($row) {
                    return $row->jumlah * $row->harga;
                });

                $hpp = abs($hpp);
                return _round($hpp);
            },
        );
    }
}
