<?php

namespace App\Livewire\Admin\Pembelian\FakturPembelian;

use App\Models\Pembelian\FakturPembelian;
use App\Services\Pembelian\FakturPembelianService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = FakturPembelian::class;
    public $menuTitle = 'Faktur Pembelian';
    public FakturPembelian $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.pembelian.faktur-pembelian.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function processDelete($id)
    {
        $obj = FakturPembelian::findOrFail($id);
        FakturPembelianService::destroy($obj);
    }

    public function render()
    {
        return view('admin.pembelian.faktur-pembelian.show')
            ->layout($this->layout);
    }
}
