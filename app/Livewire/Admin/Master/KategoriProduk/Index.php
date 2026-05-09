<?php

namespace App\Livewire\Admin\Master\KategoriProduk;

use App\Models\Master\KategoriProduk;
use App\Services\Master\KategoriProdukService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = KategoriProduk::class;
    public $menuTitle = 'Kategori Produk';
    protected $export_filename = 'kategori_produk';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.kategori-produk.index-export';
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
        $obj = KategoriProduk::findOrFail($id);
        KategoriProdukService::destroy($obj);
    }

    private function getQuery()
    {
        return KategoriProduk::query()
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

        return view('admin.master.kategori-produk.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
