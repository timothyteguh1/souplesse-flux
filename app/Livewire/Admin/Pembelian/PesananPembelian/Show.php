<?php

namespace App\Livewire\Admin\Pembelian\PesananPembelian;

use App\Models\Pembelian\PesananPembelian;
use App\Services\Pembelian\PesananPembelianService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = PesananPembelian::class;
    public $menuTitle = 'PO Pembelian';
    public PesananPembelian $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.pembelian.pesanan-pembelian.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function approve($id)
    {
        $pesananPembelian = PesananPembelian::find($id);
        PesananPembelianService::updateStatusTerima($pesananPembelian);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Pesanan Pembelian telah di-Approve.');
    }

    public function tolak($id)
    {
        $pesananPembelian = PesananPembelian::find($id);
        PesananPembelianService::updateStatusTolak($pesananPembelian);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Pesanan Pembelian telah di-Tolak.');
    }

    public function tutup($id)
    {
        $pesananPembelian = PesananPembelian::find($id);
        PesananPembelianService::updateStatusTutup($pesananPembelian);
        $this->obj = $this->obj->refresh();

        session()->flash('flash_success', 'Pesanan Pembelian telah di-Tutup.');
    }

    public function processDelete($id)
    {
        $obj = PesananPembelian::findOrFail($id);
        PesananPembelianService::destroy($obj);
    }

    public function render()
    {
        return view('admin.pembelian.pesanan-pembelian.show')
            ->layout($this->layout);
    }
}
