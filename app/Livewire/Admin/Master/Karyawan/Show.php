<?php

namespace App\Livewire\Admin\Master\Karyawan;

use App\Models\Master\Karyawan;
use App\Services\Master\KaryawanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Karyawan::class;
    public $menuTitle = 'Salesman';
    public Karyawan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Karyawan::findOrFail($id);
        KaryawanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.karyawan.show')
            ->layout($this->layout);
    }
}
