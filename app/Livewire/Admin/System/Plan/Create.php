<?php

namespace App\Livewire\Admin\System\Plan;

use Livewire\Component;
use App\Models\Plan;
use App\Traits\Livewire\WithCreateForm;
use App\Services\PlanService;

class Create extends Component
{
    use WithCreateForm;

    public $model = Plan::class;
    public $menuTitle = 'Plan';
    public $kode;
    public $nama;
    public $jumlah_cabang;
    public $jumlah_user;
    public $harga;
    public $masa_aktif_hari;
    public $keterangan;

    protected function rules(): array
    {
        return [
            'kode' => [],
            'nama' => ['string', 'required'],
            'jumlah_cabang' => ['integer', 'required'],
            'jumlah_user' => ['integer', 'required'],
            'harga' => ['numeric', 'required'],
            'masa_aktif_hari' => ['integer', 'required'],
            'keterangan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
    }

    public function submit($validated)
    {
        return PlanService::create($validated);
    }

    public function render()
    {
        return view('admin.system.plan.create')->layout($this->layout);
    }
}
