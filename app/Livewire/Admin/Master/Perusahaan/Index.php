<?php

namespace App\Livewire\Admin\Master\Perusahaan;

use App\Models\Master\Perusahaan;
use App\Services\Master\PerusahaanService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Perusahaan::class;
    public $menuTitle = 'Perusahaan';
    protected $export_filename = 'perusahaan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.perusahaan.index-export';
    public $keyword;
    public $status;

    public function mount()
    {
        $this->checkPermissionIndexGate();
    }

    public function processDelete($id)
    {
        $obj = Perusahaan::findOrFail($id);
        PerusahaanService::destroy($obj);
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
        return Perusahaan::query()
            ->keywordSearch($this->keyword, ['nama', 'alamat', 'kota', 'telp', 'email'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.perusahaan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
