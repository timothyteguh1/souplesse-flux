<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualan;

use App\Models\Penjualan\FakturPenjualan;
use App\Services\Penjualan\FakturPenjualanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Livewire\Component;
use App\Utilities\Constants\Const_JenisTransaksi;

class Index extends Component
{
    use WithIndexForm;

    public $model = FakturPenjualan::class;
    public $menuTitle = 'Faktur Penjualan';
    protected $export_filename = 'faktur_penjualan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.penjualan.faktur-penjualan.index-export';
    public $tanggal;
    public $keyword;
    public $customer_id;
    public $gudang_id;
    public $gudang_ids;
    public $status_pengiriman;
    public $status;
    public $cabang_ids;
    public $jenis_transaksis;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->tanggal = _get_default_date_range();
        $this->cabang_ids = session()->get('cabang_ids');
        $this->gudang_ids = auth()->user()->getPermissionGudangIds();
        $this->jenis_transaksis = [Const_JenisTransaksi::FAKTUR_PENJUALAN_LUNAS, Const_JenisTransaksi::FAKTUR_PENJUALAN_KREDIT];
    }

    public function processDelete($id)
    {
        $obj = FakturPenjualan::findOrFail($id);
        FakturPenjualanService::destroy($obj);
    }

    private function getQuery()
    {
        $this->gudang_ids = $this->gudang_id ? [$this->gudang_id] : $this->gudang_ids;
        return FakturPenjualan::query()
            ->with(['customer', 'gudang', 'cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->whereIn('jenis_transaksi', $this->jenis_transaksis)
            ->where(function ($query) {
                $query->whereIn('gudang_id', $this->gudang_ids)
                    ->orWhere('gudang_id', null);
            })
            ->keywordSearch($this->keyword, ['kode'])
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateRange($query, $this->tanggal, 'tanggal');
            })
            ->when($this->customer_id, function ($query) {
                return $query->where('customer_id', $this->customer_id);
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            })
            ->when($this->status_pengiriman, function ($query) {
                return $query->where('status_pengiriman', $this->status_pengiriman);
            });
    }

    public function print($id)
    {
        $obj = FakturPenjualan::findOrFail($id);
        $view = view('admin.penjualan.faktur-penjualan.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.penjualan.faktur-penjualan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
