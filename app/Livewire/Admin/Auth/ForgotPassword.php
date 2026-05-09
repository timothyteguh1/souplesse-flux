<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email;
    protected $rules = [
        'email' => ['required', 'string', 'email', 'exists:users,email'],
    ];

    public function process()
    {
        $this->validate();

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->email = '';

            session()->flash('flash_success', 'Password reset email sent!');

            return;
        }

        $this->addError('email', trans($status));
    }

    public function render()
    {
        return view('admin.auth.forgot-password')
            ->layout('admin.components.layouts.auth');
    }
}
