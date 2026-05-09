<?php

namespace App\Livewire\Admin\Pembelian\ReturPembelian;

use App\Models\Pembelian\ReturPembelian;
use App\Services\Pembelian\ReturPembelianService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = ReturPembelian::class;
    public $menuTitle = 'Retur Pembelian';
    public ReturPembelian $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.pembelian.retur-pembelian.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function processDelete($id)
    {
        $obj = ReturPembelian::findOrFail($id);
        ReturPembelianService::destroy($obj);
    }

    public function render()
    {
        return view('admin.pembelian.retur-pembelian.show')
            ->layout($this->layout);
    }
}
