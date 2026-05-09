<?php

namespace App\Livewire\Admin\Master\Satuan;

use App\Models\Master\Satuan;
use App\Services\Master\SatuanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Satuan::class;
    public $menuTitle = 'Satuan';
    public Satuan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Satuan::findOrFail($id);
        SatuanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.satuan.show')
            ->layout($this->layout);
    }
}
