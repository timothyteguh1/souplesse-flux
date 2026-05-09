<?php

namespace App\Models\Master;

use App\Traits\HasCoreFeature;
use Illuminate\Database\Eloquent\Model;

class CustomerDiskon extends Model
{
    use HasCoreFeature;

    protected $fillable = [
        'customer_id',
        'metode_pembayaran',
        'diskon',
        'keterangan',
    ];
}
