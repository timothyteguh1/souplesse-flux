<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualanViaSo;

use App\Models\Penjualan\FakturPenjualan;
use App\Services\Penjualan\FakturPenjualanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = FakturPenjualan::class;
    public $menuTitle = 'Faktur Penjualan Via SO';
    public FakturPenjualan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.penjualan.faktur-penjualan-via-so.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function processDelete($id)
    {
        $obj = FakturPenjualan::findOrFail($id);
        FakturPenjualanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.penjualan.faktur-penjualan-via-so.show')
            ->layout($this->layout);
    }
}
