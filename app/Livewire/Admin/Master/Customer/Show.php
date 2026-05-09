<?php

namespace App\Livewire\Admin\Master\Customer;

use App\Models\Master\Customer;
use App\Services\Master\CustomerService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Customer::class;
    public $menuTitle = 'Customer';
    public Customer $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Customer::findOrFail($id);
        CustomerService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.customer.show')
            ->layout($this->layout);
    }
}
