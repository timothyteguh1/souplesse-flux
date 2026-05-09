<?php

namespace App\Livewire\Admin\Master\Karyawan;

use App\Models\Master\Karyawan;
use App\Services\Master\KaryawanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Karyawan::class;
    public $menuTitle = 'Salesman';
    protected $export_filename = 'salesman';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.karyawan.index-export';
    public $keyword;
    public $status;
    public $cabang_ids;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->cabang_ids = session()->get('cabang_ids');
    }

    public function processDelete($id)
    {
        $obj = Karyawan::findOrFail($id);
        KaryawanService::destroy($obj);
    }

    private function getQuery()
    {
        return Karyawan::query()
            ->with(['cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->keyword, ['kode', 'nama'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.karyawan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
