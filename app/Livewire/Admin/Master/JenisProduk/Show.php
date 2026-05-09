<?php

namespace App\Livewire\Admin\Master\JenisProduk;

use App\Models\Master\JenisProduk;
use App\Services\Master\JenisProdukService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = JenisProduk::class;
    public $menuTitle = 'Jenis Produk';
    public JenisProduk $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = JenisProduk::findOrFail($id);
        JenisProdukService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.jenis-produk.show')
            ->layout($this->layout);
    }
}
