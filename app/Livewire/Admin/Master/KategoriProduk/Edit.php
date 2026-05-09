<?php

namespace App\Livewire\Admin\Master\KategoriProduk;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Master\KategoriProduk;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\KategoriProdukService;

class Edit extends Component
{
    use WithEditForm;

    public $model = KategoriProduk::class;
    public $menuTitle = 'Kategori Produk';
    public KategoriProduk $obj;
    public $kode;
    public $nama;
    public $keterangan;
    public $status = 0;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string',
                'required',
                Rule::unique(KategoriProduk::getTableName(), 'nama')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
            ],
            'keterangan' => [],
            'status' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->nama = $this->obj->nama;
        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;
    }

    public function submit($validated)
    {
        KategoriProdukService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.kategori-produk.edit')
            ->layout($this->layout);
    }
}
