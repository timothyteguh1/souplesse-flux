<?php

namespace App\Livewire\Admin\Master\Cabang;

use App\Models\Master\Cabang;
use App\Services\Master\CabangService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Cabang::class;
    public $menuTitle = 'Cabang';
    public Cabang $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Cabang::findOrFail($id);
        CabangService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.cabang.show')
            ->layout($this->layout);
    }
}
