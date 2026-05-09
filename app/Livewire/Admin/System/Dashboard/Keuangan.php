<?php

namespace App\Livewire\Admin\System\Dashboard;

use App\Traits\Livewire\WithDashboardForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Keuangan extends Component
{
    use WithDashboardForm;

    public $menuTitle = 'Dashboard Keuangan';

    public function mount()
    {
        abort_if(Gate::none([
            'dashboard.keuangan.jumlah-transaksi',
            'dashboard.keuangan.saldo-kas',
            'dashboard.keuangan.kas-keluar',
            'dashboard.keuangan.kas-masuk',
        ]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('admin.system.dashboard.keuangan')
            ->layout($this->layout);
    }
}
