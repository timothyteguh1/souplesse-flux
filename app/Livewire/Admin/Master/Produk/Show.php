<?php

namespace App\Livewire\Admin\Master\Produk;

use App\Models\Master\Produk;
use App\Services\Master\ProdukService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Produk::class;
    public $menuTitle = 'Produk';
    public Produk $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Produk::findOrFail($id);
        ProdukService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.produk.show')
            ->layout($this->layout);
    }
}
