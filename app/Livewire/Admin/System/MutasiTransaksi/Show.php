<?php

namespace App\Livewire\Admin\System\MutasiTransaksi;

use App\Models\System\MutasiTransaksi;
use App\Traits\Livewire\WithShowForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = MutasiTransaksi::class;
    public $menuTitle = 'Mutasi Transaksi';
    public MutasiTransaksi $obj;

    public function mount()
    {
        abort_if(Gate::none([$this->model::permissionShow()]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        return view('admin.system.mutasi-transaksi.show')
            ->layout($this->layout);
    }
}
