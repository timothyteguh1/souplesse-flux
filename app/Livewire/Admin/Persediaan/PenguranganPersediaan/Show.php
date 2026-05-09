<?php

namespace App\Livewire\Admin\Persediaan\PenguranganPersediaan;

use App\Models\Persediaan\PenguranganPersediaan;
use App\Services\Persediaan\PenguranganPersediaanService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = PenguranganPersediaan::class;
    public $menuTitle = 'Penyesuaian Kurang';
    public PenguranganPersediaan $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function print()
    {
        $view = view('admin.persediaan.pengurangan-persediaan.print', [
            'obj' => $this->obj,
        ]);

        $this->cetakRawHtml($view->render());
    }

    public function processDelete($id)
    {
        $obj = PenguranganPersediaan::findOrFail($id);
        PenguranganPersediaanService::destroy($obj);
    }

    public function render()
    {
        return view('admin.persediaan.pengurangan-persediaan.show')
            ->layout($this->layout);
    }
}
