<?php

namespace App\Livewire\Admin\System\Dashboard;

use App\Traits\Livewire\WithDashboardForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Pembelian extends Component
{
    use WithDashboardForm;

    public $menuTitle = 'Dashboard Pembelian';

    public function mount()
    {
        abort_if(Gate::none([
            'dashboard.pembelian.pembelian-produk',
            'dashboard.pembelian.jumlah-transaksi',
        ]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('admin.system.dashboard.pembelian')
            ->layout($this->layout);
    }
}
