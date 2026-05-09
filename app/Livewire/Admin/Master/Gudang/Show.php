<?php

namespace App\Livewire\Admin\Master\Gudang;

use App\Models\Master\Gudang;
use App\Services\Master\GudangService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Gudang::class;
    public $menuTitle = 'Gudang';
    public Gudang $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Gudang::findOrFail($id);
        GudangService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.gudang.show')
            ->layout($this->layout);
    }
}
