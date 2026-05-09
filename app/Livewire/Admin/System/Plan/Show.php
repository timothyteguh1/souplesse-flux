<?php

namespace App\Livewire\Admin\System\Plan;

use App\Models\Plan;
use App\Services\PlanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Plan::class;
    public $menuTitle = 'Plan';
    public Plan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Plan::findOrFail($id);
        PlanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.system.plan.show')
            ->layout($this->layout);
    }
}
