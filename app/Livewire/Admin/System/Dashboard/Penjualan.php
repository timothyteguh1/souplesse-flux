<?php

namespace App\Livewire\Admin\System\Dashboard;

use App\Traits\Livewire\WithDashboardForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Penjualan extends Component
{
    use WithDashboardForm;

    public $menuTitle = 'Dashboard Penjualan';

    public function mount()
    {
        abort_if(Gate::none([
            'dashboard.penjualan.penjualan-produk',
            'dashboard.penjualan.penjualan-produk-per-jenis-transaksi',
            'dashboard.penjualan.jumlah-transaksi',
        ]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('admin.system.dashboard.penjualan')
            ->layout($this->layout);
    }
}
