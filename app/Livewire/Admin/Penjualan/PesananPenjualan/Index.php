<?php

namespace App\Livewire\Admin\Penjualan\PesananPenjualan;

use Livewire\Component;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\PesananPenjualan;
use App\Utilities\QueryHelpers\QH_DateTime;
use App\Services\Penjualan\PesananPenjualanService;

class Index extends Component
{
    use WithIndexForm;

    public $model = PesananPenjualan::class;
    public $menuTitle = 'Pesanan Penjualan';
    protected $export_filename = 'pesanan_penjualan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.penjualan.pesanan-penjualan.index-export';
    public $tanggal;
    public $keyword;
    public $customer_id;
    public $user_id;
    public $status;
    public $cabang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->tanggal = _get_default_date_range();
        $this->cabang_ids = session()->get('cabang_ids');
    }

    public function processDelete($id)
    {
        $obj = PesananPenjualan::findOrFail($id);
        PesananPenjualanService::destroy($obj);
    }

    private function getQuery()
    {
        return PesananPenjualan::query()
            ->with(['customer', 'cabang', 'karyawan'])
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
            })
            ->when($this->user_id, function ($query) {
                return $query->whereHas('createdBy', function ($q) {
                    $q->where('causer_id', $this->user_id);
                });
            });
    }

    public function approve($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);
        PesananPenjualanService::updateStatusTerima($pesananPenjualan);

        session()->flash('flash_success', 'Pesanan Penjualan telah di-Terima.');
    }

    public function tolak($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);
        PesananPenjualanService::updateStatusTolak($pesananPenjualan);

        session()->flash('flash_success', 'Pesanan Penjualan telah di-Tolak.');
    }

    public function tutup($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);
        PesananPenjualanService::updateStatusTutup($pesananPenjualan);

        session()->flash('flash_success', 'Pesanan Penjualan telah di-Tupup.');
    }

    public function print($id)
    {
        $obj = PesananPenjualan::findOrFail($id);
        $view = view('admin.penjualan.pesanan-penjualan.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.penjualan.pesanan-penjualan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
