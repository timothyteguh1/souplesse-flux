<?php

namespace App\Livewire\Admin\Pembelian\ReturPembelian;

use App\Models\Pembelian\ReturPembelian;
use App\Services\Pembelian\ReturPembelianService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = ReturPembelian::class;
    public $menuTitle = 'Retur Pembelian';
    protected $export_filename = 'retur_pembelian';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.pembelian.retur-pembelian.index-export';
    public $tanggal;
    public $keyword;
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
        $obj = ReturPembelian::findOrFail($id);
        ReturPembelianService::destroy($obj);
    }

    private function getQuery()
    {
        $this->gudang_ids = $this->gudang_id ? [$this->gudang_id] : $this->gudang_ids;
        return ReturPembelian::query()
            ->with(['supplier', 'gudang', 'cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->whereIn('gudang_id', $this->gudang_ids)
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
        $obj = ReturPembelian::findOrFail($id);
        $view = view('admin.pembelian.retur-pembelian.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.pembelian.retur-pembelian.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
