<?php

namespace App\Livewire\Admin\Master\Supplier;

use App\Models\Master\Supplier;
use App\Services\Master\SupplierService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Supplier::class;
    public $menuTitle = 'Supplier';
    public Supplier $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Supplier::findOrFail($id);
        SupplierService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.supplier.show')
            ->layout($this->layout);
    }
}
