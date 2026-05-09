<?php

namespace App\Livewire\Admin\System\Profile;

use Exception;
use Livewire\Component;

class Umum extends Component
{
    public $name;
    public $username;
    public $email;

    protected function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'username' => ['string', 'required', 'unique:users,username,' . auth()->id()],
            'email' => ['email', 'nullable', 'unique:users,email,' . auth()->id()],
        ];
    }

    public function mount()
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            auth()->user()->update($validated);

            session()->flash('flash_success', 'Data telah diubah.');
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.system.profile.umum');
    }
}
