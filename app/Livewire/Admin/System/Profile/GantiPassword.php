<?php

namespace App\Livewire\Admin\System\Profile;

use App\Rules\MatchOldPassword;
use Exception;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class GantiPassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    protected function rules(): array
    {
        return [
            'current_password' => ['required', new MatchOldPassword()],
            'password' => ['required', 'confirmed'],
            'password_confirmation' => ['required'],
        ];
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            auth()->user()->update([
                'password' => Hash::make($validated['password']),
            ]);

            session()->flash('flash_success', 'Password Account telah diubah.');
            $this->reset();
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.system.profile.ganti-password')
            ->layout('admin.components.layouts.app');
    }
}
