<?php

namespace App\Livewire\Admin\Penjualan\ReturPenjualan;

use App\Models\Penjualan\ReturPenjualan;
use App\Services\Penjualan\ReturPenjualanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = ReturPenjualan::class;
    public $menuTitle = 'Retur Penjualan';
    protected $export_filename = 'retur_penjualan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.penjualan.retur-penjualan.index-export';
    public $tanggal;
    public $keyword;
    public $customer_id;
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
        $obj = ReturPenjualan::findOrFail($id);
        ReturPenjualanService::destroy($obj);
    }

    private function getQuery()
    {
        $this->gudang_ids = $this->gudang_id ? [$this->gudang_id] : $this->gudang_ids;
        return ReturPenjualan::query()
            ->with(['customer', 'gudang', 'cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->whereIn('gudang_id', $this->gudang_ids)
            ->keywordSearch($this->keyword, ['kode', 'keterangan'])
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateRange($query, $this->tanggal, 'tanggal');
            })
            ->when($this->customer_id, function ($query) {
                return $query->where('customer_id', $this->customer_id);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function print($id)
    {
        $obj = ReturPenjualan::findOrFail($id);
        $view = view('admin.penjualan.retur-penjualan.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.penjualan.retur-penjualan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
