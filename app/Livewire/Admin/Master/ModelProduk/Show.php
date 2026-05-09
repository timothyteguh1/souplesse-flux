<?php

namespace App\Livewire\Admin\Master\ModelProduk;

use App\Models\Master\ModelProduk;
use App\Services\Master\ModelProdukService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = ModelProduk::class;
    public $menuTitle = 'Model Produk';
    public ModelProduk $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = ModelProduk::findOrFail($id);
        ModelProdukService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.model-produk.show')
            ->layout($this->layout);
    }
}
