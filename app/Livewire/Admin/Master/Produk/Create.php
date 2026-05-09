<?php

namespace App\Livewire\Admin\Master\Produk;

use App\Models\Master\JenisProduk;
use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use Illuminate\Validation\Rule;
use App\Services\Master\ProdukService;
use App\Traits\Livewire\WithCreateForm;

class Create extends Component
{
    use WithCreateForm;

    public $model = Produk::class;
    public $menuTitle = 'Produk';
    public $cabang_id;
    public $kode;
    public $nama;
    public $kategori_produk_id;
    public $jenis_produk_id;
    public $model_produk_id;
    public $satuan_id;
    public $satuan_nama;
    public $harga_beli = null;
    public $harga_jual = null;
    public $minimal_order = 1;
    public $stok_minimum;
    public $deskripsi;
    public $keterangan;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'nama' => [
                'string',
                'required',
                Rule::unique(Produk::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'kategori_produk_id' => ['required'],
            'jenis_produk_id' => ['required'],
            'model_produk_id' => ['required'],
            'satuan_id' => ['required'],
            'harga_beli' => ['nullable', 'numeric', 'min:0'],
            'harga_jual' => ['nullable', 'numeric', 'min:0'],
            'minimal_order' => ['nullable', 'numeric', 'min:1'],
            'stok_minimum' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => [],
            'keterangan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');

        $jenisProdukPersediaan = JenisProduk::where('nama', 'Sparepart')->first();
        $this->jenis_produk_id = $jenisProdukPersediaan?->id;

        $satuanPcs = Satuan::where('nama', 'PCS')->first();
        $this->satuan_id = $satuanPcs?->id;
        $this->satuan_nama = $satuanPcs?->nama;
    }

    public function submit($validated)
    {
        $produk = ProdukService::create($validated);

        return $produk;
    }

    public function render()
    {
        return view('admin.master.produk.create')->layout($this->layout);
    }
}
