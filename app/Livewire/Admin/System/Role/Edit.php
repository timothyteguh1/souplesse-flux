<?php

namespace App\Livewire\Admin\System\Role;

use App\Models\Role;
use App\Services\RoleService;
use App\Traits\Livewire\WithEditForm;
use Livewire\Component;

class Edit extends Component
{
    use WithEditForm;

    public $model = Role::class;
    public $menuTitle = 'Role';
    public Role $obj;
    public $name;
    public $status;
    public $permissions = [];

    protected function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'status' => ['required'],
            'permissions' => ['sometimes', 'array'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->name = $this->obj->name;
        $this->status = $this->obj->status;
        $this->permissions = $this->obj->permissions->pluck('name')->toArray();
    }

    public function submit($validated)
    {
        RoleService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.system.role.edit', [
            'specialPermissions' => config('permission-lists.special_permissions'),
            'modulePermissions' => config('permission-lists.permissions'),
            'reportPermissions' => config('permission-lists.report_permissions'),
            'permissionActions' => config('permission-lists.actions'),
        ])->layout($this->layout);
    }
}
