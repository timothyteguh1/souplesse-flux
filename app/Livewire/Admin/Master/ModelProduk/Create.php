<?php

namespace App\Livewire\Admin\Master\ModelProduk;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Master\ModelProduk;
use App\Traits\Livewire\WithCreateForm;
use App\Services\Master\ModelProdukService;

class Create extends Component
{
    use WithCreateForm;

    public $model = ModelProduk::class;
    public $menuTitle = 'Model Produk';
    public $cabang_id;
    public $kode;
    public $nama;
    public $keterangan;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'nama' => [
                'string',
                'required',
                Rule::unique(ModelProduk::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
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
        return ModelProdukService::create($validated);
    }

    public function render()
    {
        return view('admin.master.model-produk.create')->layout($this->layout);
    }
}
