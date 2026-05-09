<?php

namespace App\Livewire\Admin\System\User;

use App\Models\User;
use Livewire\Component;
use App\Models\Master\Kas;
use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use App\Services\UserService;
use App\Traits\Livewire\WithEditForm;

class Edit extends Component
{
    use WithEditForm;

    public $model = User::class;
    public $menuTitle = 'User';
    public User $obj;
    public $name;
    public $email;
    public $username;
    public $password;
    public $status;
    public $role_ids;
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
            'email' => ['email:rfc', 'unique:users,email,' . $this->obj->id, 'nullable'],
            'username' => ['string', 'unique:users,username,' . $this->obj->id, 'required'],
            'password' => [],
            'role_ids' => ['array', 'required'],
            'cabang_ids' => ['array'],
            'kas_ids' => ['array'],
            'gudang_ids' => ['array'],
            'status' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();
        $this->cabangs = Cabang::all();
        $this->kas = Kas::all();
        $this->gudangs = Gudang::all();

        $this->name = $this->obj->name;
        $this->email = $this->obj->email;
        $this->username = $this->obj->username;
        $this->status = $this->obj->status;

        $this->role_ids = $this->obj->roles->pluck(['id'])->all();
        $cabangIds = $this->obj->userCabangs->pluck(['cabang_id'])->all();
        foreach ($cabangIds as $value) {
            $this->cabang_ids[$value] = true;
        }

        $kasIds = $this->obj->userKas->pluck(['kas_id'])->all();
        foreach ($kasIds as $value) {
            $this->kas_ids[$value] = true;
        }

        $gudangIds = $this->obj->userGudangs->pluck(['gudang_id'])->all();
        foreach ($gudangIds as $value) {
            $this->gudang_ids[$value] = true;
        }

        $this->isCheckedAllCabang = count($this->cabangs) == count($this->cabang_ids) ? true : false;
        $this->isCheckedAllKas = count($this->kas) == count($this->kas_ids) ? true : false;
        $this->isCheckedAllGudang = count($this->gudangs) == count($this->gudang_ids) ? true : false;
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
        UserService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.system.user.edit')
            ->layout($this->layout);
    }
}
