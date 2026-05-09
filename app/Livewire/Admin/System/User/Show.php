<?php

namespace App\Livewire\Admin\System\User;

use App\Models\Master\Cabang;
use App\Models\Master\Gudang;
use App\Models\Master\Kas;
use App\Models\User;
use App\Services\UserService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = User::class;
    public $menuTitle = 'User';
    public User $obj;
    protected $listeners = ['delete'];
    public $selectedCabangIds;
    public $selectedKasIds;
    public $selectedGudangIds;
    public $cabangs;
    public $kas;
    public $gudangs;

    public function mount()
    {
        $this->checkPermissionShowGate();
        $this->selectedCabangIds = $this->obj->userCabangs()->with('cabang')->pluck('cabang_id')->toArray();
        $this->selectedKasIds = $this->obj->userKas()->with('kas')->pluck('kas_id')->toArray();
        $this->selectedGudangIds = $this->obj->userGudangs()->with('gudang')->pluck('gudang_id')->toArray();
        $this->cabangs = Cabang::all();
        $this->kas = Kas::all();
        $this->gudangs = Gudang::all();
    }

    public function processDelete($id)
    {
        $obj = User::findOrFail($id);
        UserService::destroy($obj);
        session()->flash('flash_success', $this->menuTitle . ' telah dihapus.');
    }

    public function render()
    {
        return view('admin.system.user.show')
            ->layout($this->layout);
    }
}
