<?php

namespace App\Livewire\Admin\Master\Satuan;

use App\Models\Master\Satuan;
use App\Services\Master\SatuanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Satuan::class;
    public $menuTitle = 'Satuan';
    protected $export_filename = 'satuan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.satuan.index-export';
    public $keyword;
    public $status;
    public $cabang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->cabang_ids = session()->get('cabang_ids');
    }

    public function processDelete($id)
    {
        $obj = Satuan::findOrFail($id);
        SatuanService::destroy($obj);
    }

    private function getQuery()
    {
        return Satuan::query()
            ->with(['cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->keyword, ['kode', 'nama'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.satuan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
