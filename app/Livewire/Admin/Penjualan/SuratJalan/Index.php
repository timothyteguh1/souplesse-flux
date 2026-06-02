<?php

namespace App\Livewire\Admin\Penjualan\SuratJalan;

use App\Models\Penjualan\SuratJalan;
use App\Services\Penjualan\SuratJalanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use App\Utilities\SelectHelpers\Master\SH_Customer;
use App\Utilities\SelectHelpers\System\SH_Status;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = SuratJalan::class;
    public $menuTitle = 'Surat Jalan';
    protected $export_filename = 'penerimaan_penjualan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.penjualan.surat-jalan.index-export';
    public $tanggal;
    public $keyword;
    public $customer_id;
    public $status;
    public $cabang_ids;
    public $jenis_transaksis;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->tanggal = _get_default_date_range();
        $this->cabang_ids = session()->get('cabang_ids');
    }

    #[Computed(persist: true)]
    public function optionsStatus()
    {
        return SH_Status::surat_jalan();
    }

    #[Computed(persist: true)]
    public function optionsCustomerId()
    {
        return SH_Customer::active();
    }

    public function processDelete($id)
    {
        $obj = SuratJalan::findOrFail($id);
        SuratJalanService::destroy($obj);
    }

    private function getQuery()
    {
        return SuratJalan::query()
            ->with(['customer', 'cabang', 'pesananPenjualan', 'karyawan'])
            ->whereIn('cabang_id', $this->cabang_ids)
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
        $obj = SuratJalan::findOrFail($id);
        $view = view('admin.penjualan.surat-jalan.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.penjualan.surat-jalan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
