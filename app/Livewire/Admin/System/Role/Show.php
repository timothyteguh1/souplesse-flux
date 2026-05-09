<?php

namespace App\Livewire\Admin\System\Role;

use App\Models\Role;
use App\Services\RoleService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Role::class;
    public $menuTitle = 'Role';
    public Role $obj;
    public $permissions;
    public $specialPermissions;
    public $modulePermissions;
    public $reportPermissions;
    public $permissionActions;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();

        $this->permissions = $this->obj->permissions->pluck('name')->toArray();
        $this->specialPermissions = config('permission-lists.special_permissions');
        $this->modulePermissions = config('permission-lists.permissions');
        $this->reportPermissions = config('permission-lists.report_permissions');
        $this->permissionActions = config('permission-lists.actions');
    }

    public function processDelete($id)
    {
        $obj = Role::findOrFail($id);
        RoleService::destroy($obj);
        session()->flash('flash_success', $this->menuTitle . ' telah dihapus.');
    }

    public function render()
    {
        return view('admin.system.role.show')
            ->layout($this->layout);
    }
}
