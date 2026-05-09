<?php

namespace App\Livewire\Admin\Master\Produk;

use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Services\Master\ProdukService;
use App\Traits\Livewire\WithModalForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\SelectHelpers\Master\SH_JenisProduk;
use App\Utilities\SelectHelpers\Master\SH_KategoriProduk;
use App\Utilities\SelectHelpers\Master\SH_Merk;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Utilities\SelectHelpers\Master\SH_Satuan;
use App\Utilities\SelectHelpers\Master\SH_SubKategoriProduk;
use App\Utilities\SelectHelpers\SH_Umum;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ModalCreate extends Component
{
    use WithModalForm;

    public $model = Produk::class;
    public $form_id;
    public $cabang_id;
    public $kode;
    public $nama;
    public $satuan_dasar_id;
    public $default_satuan_beli_id;
    public $default_satuan_jual_id;
    public $default_etiket_id;
    public $tipe_produk;
    public $harga_jual_bawah = null;
    public $harga_jual_atas = null;
    public $barcode;
    public $kategori_produk_id;
    public $sub_kategori_produk_id;
    public $jenis_produk_id;
    public $merk_id;
    public $is_have_expired_date = false;
    public $is_have_no_batch = false;
    public $stok_minimum = 0;
    public $part_number;
    public $lokasi;
    public $berat = 0;
    public $panjang = 0;
    public $lebar = 0;
    public $tinggi = 0;
    public $keterangan;
    public $items = [];
    public $input_modal_satuan_id;
    public $input_konversi;
    public $input_harga_jual_bawah;
    public $input_harga_jual_atas;
    public $input_barcode;
    public $index_edit_item = null;
    public $items_paket = [];
    public $is_load_paket_produk = false;
    public $input_paket_produk_paket_id;
    public $input_paket_nama_alias;
    public $input_paket_jumlah;
    public $input_paket_satuan_id;
    public $index_edit_item_paket = null;
    public $items_foto = [];
    public $input_foto;
    protected $listeners = [
        'refreshInfo' => 'refreshInfo',
    ];

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
            'tipe_produk' => ['required'],
            'kategori_produk_id' => ['required'],
            'sub_kategori_produk_id' => [],
            'jenis_produk_id' => [],
            'merk_id' => [],
            'stok_minimum' => ['nullable', 'numeric', 'min:0'],
            'satuan_dasar_id' => ['required'],
            'default_satuan_beli_id' => [],
            'default_satuan_jual_id' => [],
            'default_etiket_id' => [],
            'harga_jual_bawah' => ['nullable', 'numeric', 'min:0'],
            'harga_jual_atas' => ['nullable', 'numeric', 'min:0'],
            'barcode' => [],
            'part_number' => [],
            'lokasi' => [],
            'berat' => [],
            'panjang' => [],
            'lebar' => [],
            'tinggi' => [],
            'is_have_expired_date' => ['boolean', 'required'],
            'is_have_no_batch' => ['boolean', 'required'],
            'keterangan' => [],

            'input_foto' => [],

            'items' => ['nullable', 'array'],
            'items.*.satuan_id' => [],
            'items.*.konversi' => [],
            'items.*.harga_jual_bawah' => ['nullable', 'numeric', 'min:0'],
            'items.*.harga_jual_atas' => ['nullable', 'numeric', 'min:0'],
            'items.*.barcode' => [],

            'items_paket' => ['nullable', 'array'],
            'items_paket.*.produk_paket_id' => ['required'],
            'items_paket.*.satuan_id' => ['required'],
            'items_paket.*.nama_alias' => ['required'],
            'items_paket.*.jumlah' => ['nullable', 'numeric', 'min:1'],
        ];
    }

    public function refreshInfo($params = null)
    {
        if (!$this->checkPermissionCreate()) {
            return;
        }

        $this->reset([
            'cabang_id',
            'kode',
            'nama',
            'satuan_dasar_id',
            'default_satuan_beli_id',
            'default_satuan_jual_id',
            'default_etiket_id',
            'tipe_produk',
            'harga_jual_bawah',
            'harga_jual_atas',
            'barcode',
            'kategori_produk_id',
            'sub_kategori_produk_id',
            'jenis_produk_id',
            'merk_id',
            'is_have_expired_date',
            'is_have_no_batch',
            'stok_minimum',
            'part_number',
            'lokasi',
            'berat',
            'panjang',
            'lebar',
            'tinggi',
            'keterangan',
            'items',
            'input_modal_satuan_id',
            'input_konversi',
            'input_harga_jual_bawah',
            'input_harga_jual_atas',
            'input_barcode',
            'index_edit_item',
            'items_paket',
            'input_paket_produk_paket_id',
            'input_paket_nama_alias',
            'input_paket_jumlah',
            'input_paket_satuan_id',
            'index_edit_item_paket',
            'items_foto',
            'input_foto',
        ]);

        $this->cabang_id = session()->get('cabang_id');
        $this->form_id = $params['form_id'];
        $this->showModal($this->form_id);
    }

    #[Computed(persist: true)]
    public function optionsKategoriProdukId()
    {
        return SH_KategoriProduk::active();
    }

    #[Computed(persist: true)]
    public function optionsJenisProdukId()
    {
        return SH_JenisProduk::active();
    }

    // #[Computed(persist: true)]
    // public function optionsMerkId()
    // {
    //     return SH_Merk::active();
    // }

    #[Computed(persist: true)]
    public function optionsSatuanDasarId()
    {
        return SH_Satuan::active();
    }

    #[Computed(persist: true)]
    public function optionsDefaultSatuanBeliId()
    {
        return SH_Satuan::active();
    }

    #[Computed(persist: true)]
    public function optionsDefaultSatuanJualId()
    {
        return SH_Satuan::active();
    }

    #[Computed(persist: true)]
    public function optionsInputSatuanId()
    {
        return SH_Satuan::active();
    }

    // #[Computed(persist: true)]
    // public function optionsTipeProduk()
    // {
    //     return SH_Umum::tipeProduks();
    // }

    #[Computed(persist: true)]
    public function optionsInputPaketProdukPaketId()
    {
        return SH_Produk::activeTanpaPaket();
    }

    public function updatedKategoriProdukId()
    {
        $this->reset('sub_kategori_produk_id');

        $options = [];
        if ($this->kategori_produk_id) {
            $options = SH_SubKategoriProduk::kategori($this->kategori_produk_id);
        }

        $this->dispatch('refresh_dropdown_sub_kategori_produk_id', [
            'options' => $options,
            'value' => null,
        ]);
    }

    public function updatedTipeProduk()
    {
        if ($this->tipe_produk != Const_Umum::TIPE_PRODUK_PAKET) {
            $this->items_paket = [];
        }

        if ($this->tipe_produk == Const_Umum::TIPE_PRODUK_PAKET && !$this->is_load_paket_produk) {
            $this->is_load_paket_produk = true;
            $options = $this->optionsInputPaketProdukPaketId;

            $this->dispatch('refresh_dropdown_input_paket_produk_paket_id', [
                'options' => $options,
                'value' => null,
            ]);
        }
    }

    public function updatedInputPaketProdukPaketId()
    {
        $produk = Produk::find($this->input_paket_produk_paket_id);

        if (!$produk) {
            $this->dispatch('refresh_dropdown_input_paket_satuan_id', [
                'options' => [],
                'value' => null,
            ]);

            $this->input_paket_satuan_id = null;
            $this->dispatch('set_value_dropdown_input_paket_satuan_id', $this->input_paket_satuan_id);
            return;
        }

        $options = SH_Produk::satuans($produk->id);
        $this->dispatch('refresh_dropdown_input_paket_satuan_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->input_paket_satuan_id = $produk->produkSatuan?->satuan_id;
        $this->dispatch('set_value_dropdown_input_paket_satuan_id', $this->input_paket_satuan_id);
    }

    public function addItem()
    {
        if (!$this->satuan_dasar_id) {
            $this->addError('flash_danger', 'Satuan dasar harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        if ($this->satuan_dasar_id == $this->input_modal_satuan_id) {
            $this->addError('flash_danger', 'Satuan yang dipilih harus berbeda dengan satuan dasar.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_modal_satuan_id' => ['required'],
            'input_konversi' => ['required', 'numeric', 'min:1'],
            'input_harga_jual_bawah' => [],
            'input_harga_jual_atas' => [],
            'input_barcode' => [],
        ]);

        $satuan = Satuan::find($this->input_modal_satuan_id);
        $konversi = $this->input_konversi ?: 1;
        $harga_jual_bawah = $this->input_harga_jual_bawah ?: 0;
        $harga_jual_atas = $this->input_harga_jual_atas ?: 0;

        if ($harga_jual_bawah > $harga_jual_atas) {
            $this->addError('flash_danger', 'Harga jual bawah tidak boleh lebih besar dari harga jual atas.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->items[] = [
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'konversi' => $konversi,
            'harga_jual_bawah' => $harga_jual_bawah,
            'harga_jual_atas' => $harga_jual_atas,
            'barcode' => $this->input_barcode,
        ];

        $this->reset('input_modal_satuan_id', 'input_konversi', 'input_harga_jual_bawah', 'input_harga_jual_atas', 'input_barcode');
    }

    public function editItem()
    {
        if (!$this->satuan_dasar_id) {
            $this->addError('flash_danger', 'Satuan dasar harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        if ($this->satuan_dasar_id == $this->input_modal_satuan_id) {
            $this->addError('flash_danger', 'Satuan yang dipilih harus berbeda dengan satuan dasar.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_modal_satuan_id' => ['required'],
            'input_konversi' => ['required', 'numeric', 'min:1'],
            'input_harga_jual_bawah' => [],
            'input_harga_jual_atas' => [],
            'input_barcode' => [],
        ]);

        $satuan = Satuan::find($this->input_modal_satuan_id);
        $konversi = $this->input_konversi ?: 1;
        $harga_jual_bawah = $this->input_harga_jual_bawah ?: 0;
        $harga_jual_atas = $this->input_harga_jual_atas ?: 0;

        if ($harga_jual_bawah > $harga_jual_atas) {
            $this->addError('flash_danger', 'Harga jual bawah tidak boleh lebih besar dari harga jual atas.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->items[$this->index_edit_item] = [
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'konversi' => $konversi,
            'harga_jual_bawah' => $harga_jual_bawah,
            'harga_jual_atas' => $harga_jual_atas,
            'barcode' => $this->input_barcode,
        ];

        $this->reset('input_modal_satuan_id', 'input_konversi', 'input_harga_jual_bawah', 'input_harga_jual_atas', 'input_barcode', 'index_edit_item');
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_modal_satuan_id = $item['satuan_id'];
        $this->input_konversi = $item['konversi'];
        $this->input_harga_jual_bawah = $item['harga_jual_bawah'];
        $this->input_harga_jual_atas = $item['harga_jual_atas'];
        $this->input_barcode = $item['barcode'];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addItemPaket()
    {
        $this->validate([
            'input_paket_produk_paket_id' => ['required'],
            'input_paket_satuan_id' => ['required'],
            'input_paket_nama_alias' => [],
            'input_paket_jumlah' => ['required'],
        ]);

        $produk_paket = Produk::find($this->input_paket_produk_paket_id);
        $satuan = Satuan::find($this->input_paket_satuan_id);
        $nama_alias = $this->input_paket_nama_alias;
        $jumlah = $this->input_paket_jumlah;

        $this->items_paket[] = [
            'produk_paket_id' => $produk_paket->id,
            'satuan_id' => $satuan->id,
            'nama_alias' => $nama_alias,
            'jumlah' => $jumlah,

            'produk_paket_nama' => $produk_paket->nama,
            'satuan_nama' => $satuan->nama,
        ];

        $this->reset('input_paket_produk_paket_id', 'input_paket_satuan_id', 'input_paket_nama_alias', 'input_paket_jumlah');
    }

    public function editItemPaket()
    {
        $this->validate([
            'input_paket_produk_paket_id' => ['required'],
            'input_paket_satuan_id' => ['required'],
            'input_paket_nama_alias' => ['required'],
            'input_paket_jumlah' => ['required'],
        ]);

        $produk_paket = Produk::find($this->input_paket_produk_paket_id);
        $satuan = Satuan::find($this->input_paket_satuan_id);
        $nama_alias = $this->input_paket_nama_alias;
        $jumlah = $this->input_paket_jumlah;

        $this->items_paket[$this->index_edit_item_paket] = [
            'produk_paket_id' => $produk_paket->id,
            'satuan_id' => $satuan->id,
            'nama_alias' => $nama_alias,
            'jumlah' => $jumlah,

            'produk_paket_nama' => $produk_paket->nama,
            'satuan_nama' => $satuan->nama,
        ];

        $this->reset('input_paket_produk_paket_id', 'input_paket_satuan_id', 'input_paket_nama_alias', 'input_paket_jumlah', 'index_edit_item_paket');
    }

    public function editPaket($index)
    {
        $this->index_edit_item_paket = $index;

        $item = $this->items_paket[$index];
        $this->input_paket_produk_paket_id = $item['produk_paket_id'];
        $this->updatedInputPaketProdukPaketId();
        $this->input_paket_satuan_id = $item['satuan_id'];
        $this->input_paket_nama_alias = $item['nama_alias'];
        $this->input_paket_jumlah = $item['jumlah'];
    }

    public function removeItemPaket($index)
    {
        unset($this->items_paket[$index]);
        $this->items_paket = array_values($this->items);
    }

    public function addItemFoto()
    {
        $this->validate([
            'input_foto' => ['required', 'image', 'mimes:jpeg,png,gif'],
        ]);

        $this->items_foto[] = $this->input_foto;
        $this->reset('input_foto');
    }

    public function removeItemFoto($index)
    {
        unset($this->items_foto[$index]);
        $this->items_foto = array_values($this->items_foto);
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            DB::beginTransaction();
            $obj = ProdukService::create($validated);
            DB::commit();

            $this->dispatch('refreshDataProduk', ['new_id' => $obj->id]);
            $this->closeModal($this->form_id);
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.master.produk.modal-create');
    }
}
