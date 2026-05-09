<?php

namespace App\Livewire\Admin\System\User;

use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use App\Models\Master\Kas;
use App\Models\User;
use App\Services\UserService;
use App\Traits\Livewire\WithCreateForm;
use Livewire\Component;

class Create extends Component
{
    use WithCreateForm;

    public $model = User::class;
    public $menuTitle = 'User';
    public $name;
    public $email;
    public $username;
    public $password;
    public $role_ids = [];
    public $cabang_ids = [];
    public $kas_ids = [];
    public $gudang_ids = [];
    public $cabangs;
    public $kas;
    public $gudangs;
    public $isCheckedAllCabang = false;
    public $isCheckedAllKas = false;
    public $isCheckedAllGudang = false;

    protected function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'email' => ['email:rfc', 'unique:users,email', 'nullable'],
            'username' => ['string', 'unique:users,username', 'required'],
            'password' => ['string', 'required'],
            'role_ids' => ['array', 'required'],
            'cabang_ids' => ['array'],
            'kas_ids' => ['array'],
            'gudang_ids' => ['array'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabangs = Cabang::all();
        foreach ($this->cabangs as $item) {
            $this->cabang_ids[$item['id']] = true;
        }
        $this->kas = Kas::all();
        $this->gudangs = Gudang::all();
    }

    public function toggleCheckAllCabang()
    {
        if ($this->isCheckedAllCabang) {
            foreach ($this->cabangs as $item) {
                $this->cabang_ids[$item['id']] = true;
            }
        } else {
            foreach ($this->cabangs as $item) {
                $this->cabang_ids[$item['id']] = false;
            }
        }
    }

    public function toggleCheckAllKas()
    {
        if ($this->isCheckedAllKas) {
            foreach ($this->kas as $item) {
                $this->kas_ids[$item['id']] = true;
            }
        } else {
            foreach ($this->kas as $item) {
                $this->kas_ids[$item['id']] = false;
            }
        }
    }

    public function toggleCheckAllGudang()
    {
        if ($this->isCheckedAllGudang) {
            foreach ($this->gudangs as $item) {
                $this->gudang_ids[$item['id']] = true;
            }
        } else {
            foreach ($this->gudangs as $item) {
                $this->gudang_ids[$item['id']] = false;
            }
        }
    }

    public function submit($validated)
    {
        return UserService::create($validated);
    }

    public function render()
    {
        return view('admin.system.user.create')
            ->layout($this->layout);
    }
}
