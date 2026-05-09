<?php

namespace App\Livewire\Admin\Master\KategoriProduk;

use App\Models\Master\KategoriProduk;
use App\Services\Master\KategoriProdukService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = KategoriProduk::class;
    public $menuTitle = 'Kategori Produk';
    public KategoriProduk $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = KategoriProduk::findOrFail($id);
        KategoriProdukService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.kategori-produk.show')
            ->layout($this->layout);
    }
}
