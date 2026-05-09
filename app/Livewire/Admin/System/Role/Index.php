<?php

namespace App\Livewire\Admin\System\Role;

use App\Models\Role;
use App\Services\RoleService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = Role::class;
    public $menuTitle = 'Role';
    protected $export_filename = 'role';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.system.role.index-export';
    public $keyword;
    public $status;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->sortField = 'name';
    }

    public function processDelete($id)
    {
        $obj = Role::findOrFail($id);
        RoleService::destroy($obj);
        session()->flash('flash_success', $this->menuTitle . ' telah dihapus.');
    }

    private function getQuery()
    {
        return Role::query()
            ->keywordSearch($this->keyword, ['name'])
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.role.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
