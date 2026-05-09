<?php

namespace App\Livewire\Admin\Master\KategoriProduk;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Master\KategoriProduk;
use App\Traits\Livewire\WithCreateForm;
use App\Services\Master\KategoriProdukService;

class Create extends Component
{
    use WithCreateForm;

    public $model = KategoriProduk::class;
    public $menuTitle = 'Kategori Produk';
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
                Rule::unique(KategoriProduk::getTableName(), 'nama')
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
        return KategoriProdukService::create($validated);
    }

    public function render()
    {
        return view('admin.master.kategori-produk.create')->layout($this->layout);
    }
}
