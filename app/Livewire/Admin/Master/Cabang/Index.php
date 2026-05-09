<?php

namespace App\Livewire\Admin\Master\Cabang;

use App\Models\Master\Cabang;
use App\Services\Master\CabangService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Cabang::class;
    public $menuTitle = 'Cabang';
    protected $export_filename = 'cabang';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.cabang.index-export';
    public $keyword;
    public $status;

    public function mount()
    {
        $this->checkPermissionIndexGate();
    }

    public function processDelete($id)
    {
        $obj = Cabang::findOrFail($id);
        CabangService::destroy($obj);
    }

    private function getParams()
    {
        return [
            'keyword' => $this->keyword,
            'status' => $this->status,
        ];
    }

    private function getQuery()
    {
        return Cabang::query()
            ->keywordSearch($this->keyword, ['nama', 'alamat', 'kota', 'telp', 'email'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.cabang.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
