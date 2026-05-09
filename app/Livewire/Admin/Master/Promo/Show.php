<?php

namespace App\Livewire\Admin\Master\Promo;

use App\Models\Master\Promo;
use App\Services\Master\PromoService;
use App\Traits\Livewire\WithShowForm;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Promo::class;
    public $menuTitle = 'Tier Diskon';
    public Promo $obj;
    protected $listeners = ['delete'];

    public function mount()
    {
        $this->checkPermissionShowGate();
    }

    public function processDelete($id)
    {
        $obj = Promo::findOrFail($id);
        PromoService::destroy($obj);
    }

    public function render()
    {
        return view('admin.master.promo.show')
            ->layout($this->layout);
    }
}
