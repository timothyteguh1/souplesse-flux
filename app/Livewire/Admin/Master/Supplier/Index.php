<?php

namespace App\Livewire\Admin\Master\Supplier;

use App\Models\Master\Supplier;
use App\Services\Master\SupplierService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Supplier::class;
    public $menuTitle = 'Supplier';
    protected $export_filename = 'supplier';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.supplier.index-export';
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
        $obj = Supplier::findOrFail($id);
        SupplierService::destroy($obj);
    }

    private function getQuery()
    {
        return Supplier::query()
            ->with(['cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->keyword, ['nama', 'alamat', 'kota', 'telp', 'email'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.supplier.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
