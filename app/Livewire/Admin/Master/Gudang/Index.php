<?php

namespace App\Livewire\Admin\Master\Gudang;

use App\Models\Master\Gudang;
use App\Services\Master\GudangService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Gudang::class;
    public $menuTitle = 'Gudang';
    protected $export_filename = 'gudang';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.gudang.index-export';
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
        $obj = Gudang::findOrFail($id);
        GudangService::destroy($obj);
    }

    private function getQuery()
    {
        return Gudang::query()
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

        return view('admin.master.gudang.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
