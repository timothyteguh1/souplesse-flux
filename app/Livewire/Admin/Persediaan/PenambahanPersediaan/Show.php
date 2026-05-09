<?php

namespace App\Livewire\Admin\Persediaan\PenambahanPersediaan;

use App\Models\Persediaan\PenambahanPersediaan;
use App\Services\Persediaan\PenambahanPersediaanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = PenambahanPersediaan::class;
    public $menuTitle = 'Penyesuaian Tambah';
    public PenambahanPersediaan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.persediaan.penambahan-persediaan.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function processDelete($id)
    {
        $obj = PenambahanPersediaan::findOrFail($id);
        PenambahanPersediaanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.persediaan.penambahan-persediaan.show')
            ->layout($this->layout);
    }
}
