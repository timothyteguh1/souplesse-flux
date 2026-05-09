<?php

namespace App\Livewire\Admin\Auth;

use App\Models\Role;
use App\Services\UserService;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;

class Register extends Component
{
    public $business_name;
    public $name;
    public $phone;
    public $username;
    public $email;
    public $password;
    public $password_confirmation;

    protected function rules()
    {
        return [
            'business_name' => ['required', 'string', 'max:255', 'min:2'],
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'phone' => ['required', 'digits_between:10,15'],
            'username' => ['required', 'unique:users,username', 'max:255', 'min:2'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(3)],
            'password_confirmation' => ['required'],
        ];
    }

    public function submit(UserService $userService)
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = $userService->create([
                'name' => $this->name,
                'phone' => $this->phone,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'is_owner' => true,
                'role_id' => Role::whereName('Owner')->firstOrFail()->id,
            ]);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();

            return to_route(_get_homepage_route());
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('livewire.auth.register')
            ->layout('components.admin.layouts.auth', [
                'title' => 'Register',
            ]);
    }
}
