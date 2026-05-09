<?php

namespace App\Livewire\Admin\System\Plan;

use App\Models\Plan;
use App\Services\PlanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Plan::class;
    public $menuTitle = 'Plan';
    protected $export_filename = 'plan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.plan.index-export';
    public $keyword;
    public $status;

    public function mount()
    {
        $this->checkPermissionIndexGate();
    }

    public function processDelete($id)
    {
        $obj = Plan::findOrFail($id);
        PlanService::destroy($obj);
    }

    private function getQuery()
    {
        return Plan::query()
            ->keywordSearch($this->keyword, ['kode', 'nama'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.plan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
