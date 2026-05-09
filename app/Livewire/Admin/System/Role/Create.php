<?php

namespace App\Livewire\Admin\System\Role;

use App\Models\Role;
use App\Services\RoleService;
use App\Traits\Livewire\WithCreateForm;
use Livewire\Component;

class Create extends Component
{
    use WithCreateForm;

    public $model = Role::class;
    public $menuTitle = 'Role';
    public $name;
    public $permissions = [];

    protected function rules(): array
    {
        return [
            'name' => ['string', 'required'],
            'permissions' => ['sometimes', 'array'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
    }

    public function submit($validated)
    {
        return RoleService::create($validated);
    }

    public function render()
    {
        return view('admin.system.role.create', [
            'specialPermissions' => config('permission-lists.special_permissions'),
            'modulePermissions' => config('permission-lists.permissions'),
            'reportPermissions' => config('permission-lists.report_permissions'),
            'permissionActions' => config('permission-lists.actions'),
        ])->layout($this->layout);
    }
}
