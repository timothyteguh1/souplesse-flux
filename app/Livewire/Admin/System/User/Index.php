<?php

namespace App\Livewire\Admin\System\User;

use App\Models\User;
use App\Services\UserService;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use Livewire\Component;

class Index extends Component
{
    use WithIndexForm;

    public $model = User::class;
    public $menuTitle = 'User';
    protected $export_filename = 'user';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.system.user.index-export';
    public $keyword;
    public $role_id;
    public $status;

    public function mount()
    {
        $this->checkPermissionIndexGate();
        $this->sortField = 'name';
    }

    public function processDelete($id)
    {
        $obj = User::findOrFail($id);
        UserService::destroy($obj);
        session()->flash('flash_success', $this->menuTitle . ' telah dihapus.');
    }

    private function getQuery()
    {
        return User::query()
            ->with('roles')
            ->keywordSearch($this->keyword, ['username', 'name', 'email'])
            ->when($this->role_id, function ($query) {
                return $query->whereHas('roles', function ($query) {
                    $query->where('id', 'LIKE', $this->role_id);
                });
            })
            ->when($this->status, function ($query) {
                return $query->where('status', $this->status);
            });
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.user.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
