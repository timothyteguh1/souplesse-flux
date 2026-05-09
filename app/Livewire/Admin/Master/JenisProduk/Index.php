<?php

namespace App\Livewire\Admin\Master\JenisProduk;

use App\Models\Master\JenisProduk;
use App\Services\Master\JenisProdukService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = JenisProduk::class;
    public $menuTitle = 'Jenis Produk';
    protected $export_filename = 'jenis_produk';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.jenis-produk.index-export';
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
        $obj = JenisProduk::findOrFail($id);
        JenisProdukService::destroy($obj);
    }

    private function getQuery()
    {
        return JenisProduk::query()
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

        return view('admin.master.jenis-produk.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
