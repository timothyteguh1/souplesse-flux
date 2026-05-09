<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualanViaSj;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\FakturPenjualan;
use App\Utilities\QueryHelpers\QH_DateTime;
use App\Utilities\Constants\Const_JenisTransaksi;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use App\Utilities\SelectHelpers\System\SH_Status;
use App\Services\Penjualan\FakturPenjualanService;
use App\Utilities\SelectHelpers\Master\SH_Customer;

class Index extends Component
{
    use WithIndexForm;

    public $model = FakturPenjualan::class;
    public $menuTitle = 'Faktur Penjualan Via SJ';
    protected $page_permissions = ['admin.penjualan.faktur-penjualan-via-sj.index'];
    protected $export_filename = 'faktur_penjualan_via_sj';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.penjualan.faktur-penjualan-via-sj.index-export';
    public $tanggal;
    public $keyword;
    public $customer_id;
    public $gudang_id;
    public $gudang_ids;
    public $status;
    public $cabang_ids;
    public $jenis_transaksis;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->tanggal = _get_default_date_range();
        $this->cabang_ids = session()->get('cabang_ids');
        $this->gudang_ids = auth()->user()->getPermissionGudangIds();
        $this->jenis_transaksis = [Const_JenisTransaksi::FAKTUR_PENJUALAN_VIA_SJ];
    }

    #[Computed(persist: true)]
    public function optionsCustomerId()
    {
        return SH_Customer::active();
    }

    #[Computed(persist: true)]
    public function optionsGudangId()
    {
        return SH_Gudang::user();
    }

    #[Computed(persist: true)]
    public function optionsStatus()
    {
        return SH_Status::faktur_penjualan();
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
            ->keywordSearch($this->keyword, ['kode'])
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
        $obj = FakturPenjualan::findOrFail($id);
        $view = view('admin.penjualan.faktur-penjualan-via-sj.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.penjualan.faktur-penjualan-via-sj.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
