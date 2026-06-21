<?php

namespace App\Livewire\Admin\Master\Produk;

use Livewire\Component;
use App\Models\Master\Produk;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\ProdukService;

class Edit extends Component
{
    use WithEditForm;

    public $model = Produk::class;
    public $menuTitle = 'Produk';
    public Produk $obj;
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
    public $status = 0;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string',
                'required',
                Rule::unique(Produk::getTableName(), 'nama')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
            ],
            'kategori_produk_id' => ['required'],
            'jenis_produk_id' => ['required'],
            'model_produk_id' => ['nullable'], // FIX: Ubah jadi nullable agar tidak error
            'satuan_id' => ['required'],
            'harga_beli' => ['nullable', 'numeric', 'min:0'],
            'harga_jual' => ['nullable', 'numeric', 'min:0'],
            'minimal_order' => ['nullable', 'numeric', 'min:0'],
            'stok_minimum' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => [],
            'keterangan' => [],
            'status' => ['required'],
        ];
    }

   public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->nama = $this->obj->nama;
        $this->jenis_produk_id = $this->obj->jenis_produk_id;
        $this->kategori_produk_id = $this->obj->kategori_produk_id;
        $this->model_produk_id = $this->obj->model_produk_id;
        
        $this->satuan_id = $this->obj->satuan_id;
        
        // --- PERBAIKAN DI SINI ---
        // Tambahkan tanda tanya (?->) dan fallback (?? '') agar tidak crash saat satuan kosong
        $this->satuan_nama = $this->obj->satuan?->nama ?? '';
        
        $this->harga_beli = $this->obj->harga_beli;
        $this->harga_jual = $this->obj->harga_jual;
        $this->minimal_order = $this->obj->minimal_order;
        $this->stok_minimum = $this->obj->stok_minimum;
        $this->deskripsi = $this->obj->deskripsi;
        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;
    }
    public function submit($validated)
    {
        ProdukService::update($this->obj, $validated);

        $this->obj->refresh();

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.produk.edit')
            ->layout($this->layout);
    }
}