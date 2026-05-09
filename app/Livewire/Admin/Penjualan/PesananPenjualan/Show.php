<?php

namespace App\Livewire\Admin\Penjualan\PesananPenjualan;

use App\Models\Penjualan\PesananPenjualan;
use App\Services\Penjualan\PesananPenjualanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = PesananPenjualan::class;
    public $menuTitle = 'Pesanan Penjualan';
    public PesananPenjualan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.penjualan.pesanan-penjualan.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function approve($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);
        PesananPenjualanService::updateStatusTerima($pesananPenjualan);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Pesanan Penjualan telah di-Approve.');
    }

    public function tolak($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);
        PesananPenjualanService::updateStatusTolak($pesananPenjualan);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Pesanan Penjualan telah di-Tolak.');
    }

    public function tutup($id)
    {
        $pesananPenjualan = PesananPenjualan::find($id);
        PesananPenjualanService::updateStatusTutup($pesananPenjualan);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Pesanan Penjualan telah di-Tutup.');
    }

    public function processDelete($id)
    {
        $obj = PesananPenjualan::findOrFail($id);
        PesananPenjualanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.penjualan.pesanan-penjualan.show')
            ->layout($this->layout);
    }
}
