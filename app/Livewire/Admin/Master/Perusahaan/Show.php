<?php

namespace App\Livewire\Admin\Master\Perusahaan;

use App\Models\Master\Perusahaan;
use App\Services\Master\PerusahaanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Perusahaan::class;
    public $menuTitle = 'Perusahaan';
    public Perusahaan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Perusahaan::findOrFail($id);
        PerusahaanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.perusahaan.show')
            ->layout($this->layout);
    }
}
