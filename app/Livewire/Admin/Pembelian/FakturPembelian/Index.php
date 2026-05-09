<?php

namespace App\Livewire\Admin\Pembelian\FakturPembelian;

use App\Models\Pembelian\FakturPembelian;
use App\Services\Pembelian\FakturPembelianService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = FakturPembelian::class;
    public $menuTitle = 'Faktur Pembelian';
    protected $export_filename = 'faktur_pembelian';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.pembelian.faktur-pembelian.index-export';
    public $tanggal;
    public $keyword;
    public $kode_faktur_supplier;
    public $supplier_id;
    public $gudang_id;
    public $gudang_ids;
    public $status;
    public $cabang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->tanggal = _get_default_date_range();
        $this->cabang_ids = session()->get('cabang_ids');
        $this->gudang_ids = auth()->user()->getPermissionGudangIds();
    }

    public function processDelete($id)
    {
        $obj = FakturPembelian::findOrFail($id);
        FakturPembelianService::destroy($obj);
    }

    private function getQuery()
    {
        $this->gudang_ids = $this->gudang_id ? [$this->gudang_id] : $this->gudang_ids;
        return FakturPembelian::query()
            ->with(['supplier', 'gudang', 'cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->whereIn('gudang_id', $this->gudang_ids)
            ->keywordSearch($this->kode_faktur_supplier, ['kode_faktur_supplier'])
            ->keywordSearch($this->keyword, ['kode', 'keterangan'])
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateRange($query, $this->tanggal, 'tanggal');
            })
            ->when($this->supplier_id, function ($query) {
                return $query->where('supplier_id', $this->supplier_id);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function print($id)
    {
        $obj = FakturPembelian::findOrFail($id);
        $view = view('admin.pembelian.faktur-pembelian.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.pembelian.faktur-pembelian.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
