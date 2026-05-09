<?php

namespace App\Livewire\Admin\Master\Produk;

use App\Models\Master\Produk;
use App\Services\Master\ProdukService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Produk::class;
    public $menuTitle = 'Produk';
    protected $export_filename = 'produk';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.produk.index-export';
    public $keyword;
    public $kategori_produk_id;
    public $jenis_produk_id;
    public $status;
    public $cabang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->cabang_ids = session()->get('cabang_ids');
    }

    public function processDelete($id)
    {
        $obj = Produk::findOrFail($id);
        ProdukService::destroy($obj);
    }

    private function getQuery()
    {
        return Produk::query()
            ->with(['kategoriProduk', 'cabang', 'jenisProduk', 'modelProduk'])
            ->keywordSearch($this->keyword, ['kode', 'nama', 'keterangan'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->when($this->kategori_produk_id, function ($query) {
                return $query->where('kategori_produk_id', $this->kategori_produk_id);
            })
            ->when($this->jenis_produk_id, function ($query) {
                return $query->where('jenis_produk_id', $this->jenis_produk_id);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.produk.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
