<?php

namespace App\Livewire\Admin\Auth;

use App\Events\UserLoggedIn;
use App\Exceptions\GeneralException;
use App\Models\Master\Cabang;
use App\Models\User;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use DanHarrin\LivewireRateLimiting\WithRateLimiting;
use Exception;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Login extends Component
{
    use WithRateLimiting;

    public $username;
    public $password;
    public bool $remember = true;
    protected $rules = [
        'username' => ['required'],
        'password' => ['required'],
        'remember' => ['required', 'boolean'],
    ];

    public function process()
    {
        $this->validate();
        try {
            $this->rateLimit(10);

            // Find by username and then email if not exist
            $field = 'username';
            $user = User::where('username', $this->username)->first();
            if (!$user) {
                $field = 'email';
                $user = User::where('email', $this->username)->first();
            }

            if (!$user) {
                throw new GeneralException('The given username/password combo did not match any accounts, please try again.');
            }

            if (!Hash::check($this->password, $user->password)) {
                throw new GeneralException('The given username/password combo did not match any accounts, please try again.');
            }

            Auth::attempt([
                $field => $this->username,
                'password' => $this->password,
            ], $this->remember);

            // cari hak akses cabang
            $cabangs = $user->getPermissionCabangIds();
            if (empty($cabangs)) {
                throw new GeneralException('User does not have access to any branch.');
            }

            // setting session cabang
            $cabang = Cabang::find($cabangs[0]);
            session()->put('cabang_id', $cabang->id);
            session()->put('cabang_kode', $cabang->kode);
            session()->put('cabang_nama', $cabang->nama);

            session()->put('cabang_ids', [$cabang->id]);

            // tetap setting agar tahu berikut nya kalau sedang remember, setelah login, akan di overide
            if ($this->remember) {
                $pipe_segments = sprintf(
                    '%s|%s|%s|%s',
                    Auth::user()->getAuthIdentifier(),
                    Auth::user()->getRememberToken(),
                    Auth::user()->getAuthPassword(),
                    collect([$cabang->id])->prepend($cabang->id)->implode(","),
                );

                Auth::getCookieJar()->queue(
                    Auth::getCookieJar()->make(Auth::getRecallerName(), $pipe_segments, 60 * 24 * 365),
                );
            }

            event(new Authenticated('web', $user));
            event(new UserLoggedIn($user));

            return redirect()->intended(route(_get_homepage_route()));
        } catch (TooManyRequestsException $exception) {
            $minutes = ceil($exception->secondsUntilAvailable / 60);
            $this->addError('flash_danger', "Too many login attempts. Please try again in $minutes minutes.");

            return false;
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception, false));

            return false;
        }
    }

    public function render()
    {
        return view('admin.auth.login')
            ->layout('admin.components.layouts.auth');
    }
}
