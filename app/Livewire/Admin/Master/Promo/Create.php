<?php

namespace App\Livewire\Admin\Master\Promo;

use Livewire\Component;
use App\Models\Master\Promo;
use App\Services\Master\PromoService;
use App\Traits\Livewire\WithCreateForm;

class Create extends Component
{
    use WithCreateForm;

    public $model = Promo::class;
    public $menuTitle = 'Tier Diskon';
    public $cabang_id;
    public $kode;
    public $produk_id;
    public $jumlah_minimum;
    public $tambahan_diskon;
    public $keterangan;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'produk_id' => ['required'],
            'jumlah_minimum' => ['required'],
            'tambahan_diskon' => ['required'],
            'keterangan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function submit($validated)
    {
        return PromoService::create($validated);
    }

    public function render()
    {
        return view('admin.master.promo.create')->layout($this->layout);
    }
}
