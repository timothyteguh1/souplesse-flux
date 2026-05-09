<?php

namespace App\Livewire\Admin\Penjualan\ReturPenjualan;

use App\Models\Penjualan\ReturPenjualan;
use App\Services\Penjualan\ReturPenjualanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = ReturPenjualan::class;
    public $menuTitle = 'Retur Penjualan';
    public ReturPenjualan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.penjualan.retur-penjualan.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function processDelete($id)
    {
        $obj = ReturPenjualan::findOrFail($id);
        ReturPenjualanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.penjualan.retur-penjualan.show')
            ->layout($this->layout);
    }
}
