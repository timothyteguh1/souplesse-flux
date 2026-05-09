<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as RulesPassword;
use Livewire\Component;

class ResetPassword extends Component
{
    public $email;
    public $password;
    public $password_confirmation;
    public bool $remember = true;
    public $token;

    protected function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'confirmed', RulesPassword::min(3)],
            'password_confirmation' => ['required'],
            'token' => ['required', 'string'],
        ];
    }

    public function mount($token)
    {
        $this->email = request()->query('email', '');
        $this->token = $token;
    }

    public function process()
    {
        $this->validate();

        $status = Password::reset([
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation,
            'token' => $this->token,
        ], function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();

            if ($this->remember) {
                $user->setRememberToken(Str::random(60));
            }

            event(new PasswordReset($user));

            Auth::login($user);
        });

        if ($status === Password::PASSWORD_RESET) {
            return to_route(_get_homepage_route());
        }

        if ($status === Password::INVALID_TOKEN) {
            $this->addError('flash_danger', trans($status));
        } elseif ($status === Password::INVALID_USER) {
            $this->addError('flash_danger', trans($status));
        } else {
            $this->addError('flash_danger', 'An error occurred, please request a new password reset link.');
        }
    }

    public function render()
    {
        return view('admin.auth.reset-password')
            ->layout('admin.components.layouts.auth');
    }
}
