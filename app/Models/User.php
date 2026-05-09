<?php

namespace App\Models;

use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use App\Models\Master\Kas;
use App\Models\Master\Perusahaan;
use App\Notifications\Admin\Auth\QueuedResetPassword;
use App\Notifications\Admin\Auth\QueuedVerifyEmail;
use App\Traits\HasCoreFeature;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Lab404\Impersonate\Models\Impersonate;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use CausesActivity;
    use HasCoreFeature;
    use HasRoles;
    use Impersonate;
    use Notifiable;

    protected $route_prefix = 'admin.system.user';
    protected $permission_prefix = 'admin.system.user';
    protected $fillable = [
        'type',
        'name',
        'username',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'password_changed_at',
        'is_active',
        'is_developer',
        'timezone',
        'last_login_at',
        'last_login_ip',
        'to_be_logged_out',
        'remember_token',
        'status',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        'active' => 'boolean',
        'last_login_at' => 'datetime:Y-m-d H:i:s',
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'to_be_logged_out' => 'boolean',
    ];
    protected $with = [
        'permissions',
        'roles',
    ];

    public function canImpersonate()
    {
        return $this->is_developer;
    }

    public function canBeImpersonated()
    {
        return !$this->is_developer;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = Hash::needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new QueuedVerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new QueuedResetPassword($token));
    }

    public function canDelete()
    {
        if ($this->is_developer) {
            return false;
        }

        $this->loadMissing('perusahaan');
        if ($this->perusahaan) {
            return false;
        }

        return true;
    }

    // region Relationships
    public function userCabangs(): HasMany
    {
        return $this->hasMany(UserCabang::class);
    }

    public function userKas(): HasMany
    {
        return $this->hasMany(UserKas::class);
    }

    public function userGudangs(): HasMany
    {
        return $this->hasMany(UserGudang::class);
    }

    public function perusahaan(): HasOne
    {
        return $this->hasOne(Perusahaan::class);
    }
    // endregion

    // region Functions
    public function isHaveSuperuserPermission()
    {
        $superUserPermission = Permission::where('name', 'superuser')->first();
        if ($superUserPermission && $this->hasPermissionTo($superUserPermission)) {
            return true;
        }

        return false;
    }

    public function getPermissionCabangIds()
    {
        if ($this->is_developer) {
            return Cabang::pluck('id')->toArray();
        }

        if ($this->isHaveSuperuserPermission()) {
            return Cabang::pluck('id')->toArray();
        }

        return $this->userCabangs->pluck('cabang_id')->toArray();
    }

    public function getPermissionKasIds()
    {
        if ($this->is_developer) {
            return Kas::pluck('id')->toArray();
        }

        if ($this->isHaveSuperuserPermission()) {
            return Kas::pluck('id')->toArray();
        }

        return $this->userKas->pluck('kas_id')->toArray();
    }

    public function getPermissionGudangIds()
    {
        if ($this->is_developer) {
            return Gudang::pluck('id')->toArray();
        }

        if ($this->isHaveSuperuserPermission()) {
            return Gudang::pluck('id')->toArray();
        }

        return $this->userGudangs->pluck('gudang_id')->toArray();
    }
    // endregion
}
