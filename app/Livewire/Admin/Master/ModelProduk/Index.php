<?php

namespace App\Livewire\Admin\Master\ModelProduk;

use App\Models\Master\ModelProduk;
use App\Services\Master\ModelProdukService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = ModelProduk::class;
    public $menuTitle = 'Model Produk';
    protected $export_filename = 'model_produk';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.model-produk.index-export';
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
        $obj = ModelProduk::findOrFail($id);
        ModelProdukService::destroy($obj);
    }

    private function getQuery()
    {
        return ModelProduk::query()
            ->with(['cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->keyword, ['kode', 'nama', 'keterangan'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.model-produk.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
