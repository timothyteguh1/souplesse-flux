<?php

namespace App\Livewire\Admin\Persediaan\PenguranganPersediaan;

use App\Models\Persediaan\PenguranganPersediaan;
use App\Services\Persediaan\PenguranganPersediaanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = PenguranganPersediaan::class;
    public $menuTitle = 'Penyesuaian Kurang';
    protected $export_filename = 'pengurangan_persediaan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.persediaan.pengurangan-persediaan.index-export';
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
        $obj = PenguranganPersediaan::findOrFail($id);
        PenguranganPersediaanService::destroy($obj);
    }

    private function getQuery()
    {
        return PenguranganPersediaan::query()
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
        $obj = PenguranganPersediaan::findOrFail($id);
        $view = view('admin.persediaan.pengurangan-persediaan.print', [
            'obj' => $obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.persediaan.pengurangan-persediaan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
