<?php

namespace App\Livewire\Admin\Master\JenisProduk;

use Livewire\Component;
use Illuminate\Validation\Rule;
use App\Models\Master\JenisProduk;
use App\Traits\Livewire\WithCreateForm;
use App\Services\Master\JenisProdukService;

class Create extends Component
{
    use WithCreateForm;

    public $model = JenisProduk::class;
    public $menuTitle = 'Jenis Produk';
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
                Rule::unique(JenisProduk::getTableName(), 'nama')
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
        return JenisProdukService::create($validated);
    }

    public function render()
    {
        return view('admin.master.jenis-produk.create')->layout($this->layout);
    }
}
