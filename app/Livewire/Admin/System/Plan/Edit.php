<?php

namespace App\Livewire\Admin\System\Plan;

use App\Models\Plan;
use App\Services\PlanService;
use App\Traits\Livewire\WithEditForm;
use Livewire\Component;

class Edit extends Component
{
    use WithEditForm;

    public $model = Plan::class;
    public $menuTitle = 'Plan';
    public Plan $obj;
    public $nama;
    public $jumlah_cabang;
    public $jumlah_user;
    public $harga;
    public $masa_aktif_hari;
    public $keterangan;
    public $status = 0;

    protected function rules(): array
    {
        return [
            'nama' => ['string', 'required'],
            'jumlah_cabang' => ['integer', 'required'],
            'jumlah_user' => ['integer', 'required'],
            'harga' => ['numeric', 'required'],
            'masa_aktif_hari' => ['integer', 'required'],
            'keterangan' => [],
            'status' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->nama = $this->obj->nama;
        $this->jumlah_cabang = $this->obj->jumlah_cabang;
        $this->jumlah_user = $this->obj->jumlah_user;
        $this->harga = $this->obj->harga;
        $this->masa_aktif_hari = $this->obj->masa_aktif_hari;
        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;
    }

    public function submit($validated)
    {
        PlanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.system.plan.edit')
            ->layout($this->layout);
    }
}
