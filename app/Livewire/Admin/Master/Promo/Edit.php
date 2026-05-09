<?php

namespace App\Livewire\Admin\Master\Promo;

use Livewire\Component;
use App\Models\Master\Promo;
use App\Services\Master\PromoService;
use App\Traits\Livewire\WithEditForm;

class Edit extends Component
{
    use WithEditForm;

    public $model = Promo::class;
    public $menuTitle = 'Tier Diskon';
    public Promo $obj;
    public $cabang_id;
    public $kode;
    public $produk_id;
    public $jumlah_minimum;
    public $tambahan_diskon;
    public $keterangan;
    public $status = 0;

    protected function rules(): array
    {
        return [
            'produk_id' => ['required'],
            'jumlah_minimum' => ['required'],
            'tambahan_diskon' => ['required'],
            'keterangan' => [],
            'status' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->cabang_id = $this->obj->cabang_id;
        $this->kode = $this->obj->kode;
        $this->produk_id = $this->obj->produk_id;
        $this->jumlah_minimum = $this->obj->jumlah_minimum;
        $this->tambahan_diskon = $this->obj->tambahan_diskon;
        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;
    }

    public function submit($validated)
    {
        PromoService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.promo.edit')
            ->layout($this->layout);
    }
}
