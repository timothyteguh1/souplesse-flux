<?php

namespace App\Livewire\Admin\Persediaan\PenambahanPersediaan;

use App\Models\Persediaan\PenambahanPersediaan;
use App\Services\Persediaan\PenambahanPersediaanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = PenambahanPersediaan::class;
    public $menuTitle = 'Penyesuaian Tambah';
    protected $export_filename = 'penambahan_persediaan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.persediaan.penambahan-persediaan.index-export';
    public $tanggal;
    public $keyword;
    public $status;
    public $cabang_ids;
    public $gudang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->tanggal = _get_default_date_range();
        $this->cabang_ids = session()->get('cabang_ids');
        $this->gudang_ids = auth()->user()->getPermissionGudangIds();
    }

    public function processDelete($id)
    {
        $obj = PenambahanPersediaan::findOrFail($id);
        PenambahanPersediaanService::destroy($obj);
    }

    private function getQuery()
    {
        return PenambahanPersediaan::query()
            ->with(['gudang', 'cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->whereIn('gudang_id', $this->gudang_ids)
            ->keywordSearch($this->keyword, ['kode', 'keterangan'])
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateRange($query, $this->tanggal, 'tanggal');
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function print($id)
    {
        $obj = PenambahanPersediaan::findOrFail($id);
        $view = view('admin.persediaan.penambahan-persediaan.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.persediaan.penambahan-persediaan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
