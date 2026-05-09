<?php

namespace App\Livewire\Admin\System\Billing;

use App\Models\Billing;
use App\Services\BillingService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Billing::class;
    public $menuTitle = 'Billing';
    public Billing $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Billing::findOrFail($id);
        BillingService::destroy($obj);
    }

    public function render()
    {
        return view('admin.system.billing.show')
            ->layout($this->layout);
    }
}
