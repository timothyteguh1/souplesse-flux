<?php

namespace App\Livewire\Admin\System\Dashboard;

use App\Traits\Livewire\WithDashboardForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Persediaan extends Component
{
    use WithDashboardForm;

    public $menuTitle = 'Dashboard Persediaan';

    public function mount()
    {
        abort_if(Gate::none([
            'dashboard.persediaan.penyesuaian-persediaan',
            'dashboard.persediaan.total-nilai-persediaan',
        ]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('admin.system.dashboard.persediaan')
            ->layout($this->layout);
    }
}
