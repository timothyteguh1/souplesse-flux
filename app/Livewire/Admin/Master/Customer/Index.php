<?php

namespace App\Livewire\Admin\Master\Customer;

use App\Models\Master\Customer;
use App\Services\Master\CustomerService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Customer::class;
    public $menuTitle = 'Customer';
    protected $export_filename = 'customer';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.master.customer.index-export';
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
        $obj = Customer::findOrFail($id);
        CustomerService::destroy($obj);
    }

    private function getQuery()
    {
        return Customer::query()
            ->with(['cabang'])
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->keyword, ['kode', 'nama', 'alamat', 'kota', 'telp', 'email'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.master.customer.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
