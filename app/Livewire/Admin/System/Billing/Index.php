<?php

namespace App\Livewire\Admin\System\Billing;

use App\Models\Billing;
use App\Services\BillingService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Billing::class;
    public $menuTitle = 'Billing';
    protected $export_filename = 'billing';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.billing.index-export';
    public $keyword;
    public $status;

    public function mount()
    {
        $this->checkPermissionIndexGate();
    }

    public function processDelete($id)
    {
        $obj = Billing::findOrFail($id);
        BillingService::destroy($obj);
    }

    private function getQuery()
    {
        return Billing::query()
            ->with(['perusahaan'])
            ->keywordSearch($this->keyword, ['kode', 'nama'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.billing.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
