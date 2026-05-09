<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class CodeCounter extends Model
{
    use HasUuids;

    protected $fillable = ['model', 'cabang_id', 'prefix', 'length', 'counter'];
}
