<?php

namespace App\Livewire\Admin\Persediaan\PenguranganPersediaan;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\ProdukSatuan;
use App\Traits\Livewire\WithCreateForm;
use App\Utilities\Functions\InventoryFunction;
use Illuminate\Validation\ValidationException;
use App\Models\Persediaan\PenguranganPersediaan;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Services\Persediaan\PenguranganPersediaanService;

class Create extends Component
{
    use WithCreateForm;

    public $model = PenguranganPersediaan::class;
    public $menuTitle = 'Penyesuaian Kurang';
    public $cabang_id;
    public $kode;
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
            'cabang_id' => ['required'],
            'kode' => [],
            'tanggal' => ['required'],
            'gudang_id' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array'],
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
        $this->checkPermissionCreateGate();

        $this->tanggal = _get_default_datetime();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function updatedGudangId()
    {
        $this->items = [];

        $this->reset('input_produk_id', 'input_satuan_id', 'input_jumlah', 'input_harga_satuan');

        $options = SH_Produk::stokGudangWithStok($this->gudang_id);
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

        $this->reset('input_harga_satuan');

        if (!$produk || !$satuan) {
            $this->dispatch('refresh_dropdown_input_expired_date', [
                'options' => [],
                'value' => null,
            ]);
            $this->dispatch('refresh_dropdown_input_no_batch', [
                'options' => [],
                'value' => null,
            ]);
            return;
        }

        $produkSatuan = ProdukSatuan::query()
            ->where('produk_id', $produk->id)
            ->where('satuan_id', $satuan->id)
            ->first();

        $konversi = $produkSatuan->konversi ?? 0;
        $hppSatuanDasar = InventoryFunction::getHpp($this->cabang_id, $produk->id);
        $hppSatuan = $hppSatuanDasar * $konversi;
        $this->input_harga_satuan = $hppSatuan;

        $options = SH_Produk::expiredDatesStokGudang($produk->id, $this->gudang_id, $this->input_satuan_id);
        $this->dispatch('refresh_dropdown_input_expired_date', [
            'options' => $options,
            'value' => null,
        ]);
        $this->input_expired_date = null;
        $this->dispatch('set_value_dropdown_input_expired_date', $this->input_expired_date);
    }

    public function updatedInputExpiredDate()
    {
        if (!$this->input_expired_date) {
            $this->dispatch('refresh_dropdown_input_no_batch', [
                'options' => [],
                'value' => null,
            ]);
            return;
        }

        $produk = Produk::find($this->input_produk_id);

        $options = SH_Produk::noBatchStokGudang($produk->id, $this->gudang_id, $this->input_satuan_id, $this->input_expired_date);
        $this->dispatch('refresh_dropdown_input_no_batch', [
            'options' => $options,
            'value' => null,
        ]);
        $this->input_no_batch = null;
        $this->dispatch('set_value_dropdown_input_no_batch', $this->input_no_batch);
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
        $stokSatuanDasar = InventoryFunction::getStok($this->cabang_id, $produk->id, $this->gudang_id);
        $stokSatuan = InventoryFunction::getStokSatuan($produk->id, $satuan->id, $stokSatuanDasar);
        $expired_date = $this->input_expired_date;
        $no_batch = $this->input_no_batch;

        if ($this->input_jumlah > $stokSatuan) {
            throw ValidationException::withMessages(['input_jumlah' => 'Jumlah tidak boleh lebih dari jumlah tersedia']);
        }

        $this->items[] = [
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
        $this->updatedInputSatuanId();

        $this->input_expired_date = $item['expired_date'];
        $this->updatedInputExpiredDate();
        $this->input_no_batch = $item['no_batch'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_harga_satuan = $item['harga_satuan'];

        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
        $this->dispatch('set_value_dropdown_input_expired_date', $this->input_expired_date);
        $this->dispatch('set_value_dropdown_input_no_batch', $this->input_no_batch);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        return PenguranganPersediaanService::create($validated);
    }

    public function render()
    {
        return view('admin.persediaan.pengurangan-persediaan.create')->layout($this->layout);
    }
}
