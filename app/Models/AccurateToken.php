<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccurateToken extends Model
{
    protected $fillable = [
        'access_token',
        'refresh_token',
        'token_type',
        'expires_in',
        'expires_at',
        'db_id',
        'db_alias',
        'db_host',
        'is_connected',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_connected' => 'boolean',
    ];

    // Selalu pakai 1 row saja (singleton)
    public static function getInstance(): ?static
    {
        return static::first();
    }

    public function isExpired(): bool
    {
        if (!$this->expires_at) return true;
        return now()->gte($this->expires_at);
    }
}