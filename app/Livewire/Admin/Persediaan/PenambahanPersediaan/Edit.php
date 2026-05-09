<?php

namespace App\Livewire\Admin\Persediaan\PenambahanPersediaan;

use App\Models\Master\Produk;
use App\Models\Master\ProdukSatuan;
use App\Models\Master\Satuan;
use App\Models\Persediaan\PenambahanPersediaan;
use App\Services\Persediaan\PenambahanPersediaanService;
use App\Traits\Livewire\WithEditForm;
use App\Utilities\Functions\InventoryFunction;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use Livewire\Component;

class Edit extends Component
{
    use WithEditForm;

    public $model = PenambahanPersediaan::class;
    public $menuTitle = 'Penyesuaian Tambah';
    public PenambahanPersediaan $obj;
    public $tanggal;
    public $gudang_id;
    public $keterangan;
    public $items = [];
    public $input_produk_id;
    public $input_satuan_id;
    public $input_expired_date;
    public $input_no_batch;
    public $input_jumlah;
    public $input_harga_satuan;
    public $index_edit_item = null;

    protected function rules(): array
    {
        return [
            'tanggal' => ['required'],
            'gudang_id' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.expired_date' => [],
            'items.*.no_batch' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->tanggal = $this->obj->tanggal;
        $this->gudang_id = $this->obj->gudang_id;
        $this->keterangan = $this->obj->keterangan;

        $this->updatedGudangId();

        $details = $this->obj->details()->with(['produk', 'satuan'])->get();
        $this->items = [];

        foreach ($details as $detail) {
            $this->items[] = [
                'id' => $detail->id,
                'produk_id' => $detail->produk_id,
                'kode' => $detail->produk->kode,
                'nama' => $detail->produk->nama,
                'satuan_id' => $detail->satuan_id,
                'satuan_nama' => $detail->satuan->nama,
                'expired_date' => $detail->expired_date,
                'no_batch' => $detail->no_batch,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan,
                'subtotal' => $detail->subtotal,
            ];
        }
    }

    public function updatedGudangId()
    {
        $this->items = [];

        $this->reset('input_produk_id', 'input_satuan_id', 'input_jumlah', 'input_harga_satuan');

        $options = SH_Produk::stokGudangWithStok($this->gudang_id, false);
        $this->dispatch('refresh_dropdown_input_produk_id', [
            'options' => $options,
            'value' => null,
        ]);
    }

    public function updatedInputProdukId()
    {
        $produk = Produk::find($this->input_produk_id);

        if (!$produk) {
            $this->dispatch('refresh_dropdown_input_satuan_id', [
                'options' => [],
                'value' => null,
            ]);

            $this->input_satuan_id = null;
            $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
            $this->updatedInputSatuanId();
            return;
        }

        $options = SH_Produk::satuansStokGudang($produk->id, $this->gudang_id);
        $this->dispatch('refresh_dropdown_input_satuan_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->input_satuan_id = null;
        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
        $this->updatedInputSatuanId();
    }

    public function updatedInputSatuanId()
    {
        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);

        if ($produk && $satuan) {
            $produkSatuan = ProdukSatuan::query()
                ->where('produk_id', $produk->id)
                ->where('satuan_id', $satuan->id)
                ->first();

            $konversi = $produkSatuan->konversi ?? 0;
            $hppSatuanDasar = InventoryFunction::getHpp($this->obj->cabang_id, $produk->id);
            $hppSatuan = $hppSatuanDasar * $konversi;
            $this->input_harga_satuan = $hppSatuan;

            return;
        }

        $this->reset('input_harga_satuan');
    }

    public function addItem()
    {
        if (!$this->gudang_id) {
            $this->addError('flash_danger', 'Gudang harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_expired_date' => [],
            'input_no_batch' => [],
            'input_jumlah' => ['required', 'min:1', 'numeric'],
            'input_harga_satuan' => ['required', 'min:1', 'numeric'],
        ]);

        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $expired_date = $this->input_expired_date;
        $no_batch = $this->input_no_batch;

        $this->items[] = [
            'id' => null,
            'produk_id' => $produk->id,
            'kode' => $produk->kode,
            'nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'expired_date' => $expired_date,
            'no_batch' => $no_batch,
            'jumlah' => $this->input_jumlah,
            'harga_satuan' => $this->input_harga_satuan,
            'subtotal' => $this->input_harga_satuan * $this->input_jumlah,
        ];

        $this->reset('input_produk_id', 'input_satuan_id', 'input_jumlah', 'input_expired_date', 'input_no_batch');
        $this->updatedInputProdukId();
    }

    public function editItem()
    {
        if (!$this->gudang_id) {
            $this->addError('flash_danger', 'Gudang harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_expired_date' => [],
            'input_no_batch' => [],
            'input_jumlah' => ['required', 'min:1', 'numeric'],
            'input_harga_satuan' => ['required', 'min:1', 'numeric'],
        ]);

        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $expired_date = $this->input_expired_date;
        $no_batch = $this->input_no_batch;

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'produk_id' => $produk->id,
            'kode' => $produk->kode,
            'nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'expired_date' => $expired_date,
            'no_batch' => $no_batch,
            'jumlah' => $this->input_jumlah,
            'harga_satuan' => $this->input_harga_satuan,
            'subtotal' => $this->input_harga_satuan * $this->input_jumlah,
        ];

        $this->reset('input_produk_id', 'input_satuan_id', 'input_jumlah', 'input_harga_satuan', 'input_expired_date', 'input_no_batch', 'index_edit_item');
        $this->updatedInputProdukId();
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_produk_id = $item['produk_id'];
        $this->updatedInputProdukId();

        $this->input_satuan_id = $item['satuan_id'];
        $this->input_expired_date = $item['expired_date'];
        $this->input_no_batch = $item['no_batch'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_harga_satuan = $item['harga_satuan'];

        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        PenambahanPersediaanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.persediaan.penambahan-persediaan.edit')
            ->layout($this->layout);
    }
}
