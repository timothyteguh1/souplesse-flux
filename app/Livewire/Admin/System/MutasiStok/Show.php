<?php

namespace App\Livewire\Admin\System\MutasiStok;

use App\Models\System\MutasiStok;
use App\Traits\Livewire\WithShowForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = MutasiStok::class;
    public $menuTitle = 'Mutasi Stok';
    public MutasiStok $obj;

    public function mount()
    {
        abort_if(Gate::none([$this->model::permissionShow()]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('admin.system.mutasi-stok.show')
            ->layout($this->layout);
    }
}
