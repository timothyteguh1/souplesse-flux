<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;

trait HasLogActivityOptions
{
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->setDescriptionForEvent(fn(string $event) => Str::of(_get_class_name($this))->headline() . " has been {$event}")
            ->useLogName($this->cabang_id ?? 'default')
            ->logAll()
            ->dontLogIfAttributesChangedOnly([
                'last_login_at', 'last_login_ip', 'updated_at', 'remember_token', 'password_changed_at', 'email_verified_at',
                'to_be_logged_out',
            ]);
    }
}
