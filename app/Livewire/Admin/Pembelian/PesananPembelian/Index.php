<?php

namespace App\Livewire\Admin\Pembelian\PesananPembelian;

use Livewire\Component;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Models\Pembelian\PesananPembelian;
use App\Utilities\QueryHelpers\QH_DateTime;
use App\Services\Pembelian\PesananPembelianService;

class Index extends Component
{
    use WithIndexForm;

    public $model = PesananPembelian::class;
    public $menuTitle = 'PO Pembelian';
    protected $export_filename = 'pesanan_pembelian';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.pembelian.pesanan-pembelian.index-export';
    public $tanggal;
    public $keyword;
    public $supplier_id;
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
        $obj = PesananPembelian::findOrFail($id);
        PesananPembelianService::destroy($obj);
    }

    private function getQuery()
    {
        return PesananPembelian::query()
            ->with(['supplier', 'cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
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

    public function approve($id)
    {
        $pesananPembelian = PesananPembelian::find($id);
        PesananPembelianService::updateStatusTerima($pesananPembelian);

        session()->flash('flash_success', 'Pesanan Pembelian telah di-Terima.');
    }

    public function tolak($id)
    {
        $pesananPembelian = PesananPembelian::find($id);
        PesananPembelianService::updateStatusTolak($pesananPembelian);

        session()->flash('flash_success', 'Pesanan Pembelian telah di-Tolak.');
    }

    public function tutup($id)
    {
        $pesananPembelian = PesananPembelian::find($id);
        PesananPembelianService::updateStatusTutup($pesananPembelian);

        session()->flash('flash_success', 'Pesanan Pembelian telah di-Tupup.');
    }

    public function print($id)
    {
        $obj = PesananPembelian::findOrFail($id);
        $view = view('admin.pembelian.pesanan-pembelian.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.pembelian.pesanan-pembelian.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
