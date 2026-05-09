<?php

namespace App\Livewire\Admin\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public function mount()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return redirect()->intended(route(_get_homepage_route()));
        }
    }

    public function resend()
    {
        Auth::user()->sendEmailVerificationNotification();

        session()->flash('flash_success', 'A fresh verification link has been sent to your email address.');
    }

    public function render()
    {
        return view('livewire.auth.verify-email')
            ->layout('components.admin.layouts.auth', [
                'title' => 'Verify Email',
            ]);
    }
}
