<?php

namespace App\Livewire\Admin\System\Setting;

use Exception;
use Livewire\Component;
use App\Models\Master\Gudang;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use Illuminate\Http\Response;
use Livewire\Attributes\Computed;
use App\Models\Master\ProdukSatuan;
use App\Models\Master\StokAwal as MasterStokAwal;
use Illuminate\Support\Facades\Gate;
use App\Services\Master\StokAwalService;
use App\Utilities\Constants\Const_Status;
use App\Utilities\Functions\InventoryFunction;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use Illuminate\Support\Facades\DB;

class StokAwal extends Component
{
    public $menuTitle = 'Stok Awal';
    public $cabang_id;
    public $gudang_id;
    public $items = [];
    public $input_produk_id;
    public $input_satuan_id;
    public $input_expired_date;
    public $input_no_batch;
    public $input_jumlah;
    public $input_harga_satuan;
    public $input_keterangan;
    public $index_edit_item = null;

    protected function rules(): array
    {
        return [
            'gudang_id' => ['required'],

            'items' => ['required', 'array'],
            'items.*.id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.expired_date' => [],
            'items.*.no_batch' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.keterangan' => [],
        ];
    }

    public function mount()
    {
        abort_if(Gate::none(['admin.system.setting.perusahaan']), Response::HTTP_FORBIDDEN);
        $this->gudang_id = Gudang::where('nama', 'Utama')->first()->id;
        $this->cabang_id = session()->get('cabang_id');

        $stokAwals = MasterStokAwal::where('status', Const_Status::AKTIF)
            ->with(['produk', 'satuan'])
            ->get();

        foreach ($stokAwals as $obj) {
            $this->items[] = [
                'id' => $obj->id,
                'produk_id' => $obj->produk_id,
                'kode' => $obj->produk?->kode,
                'nama' => $obj->produk?->nama,
                'satuan_id' => $obj->satuan_id,
                'satuan_nama' => $obj->satuan?->nama,
                'expired_date' => $obj->expired_date,
                'no_batch' => $obj->no_batch,
                'jumlah' => $obj->jumlah,
                'harga_satuan' => $obj->harga_satuan,
                'subtotal' => $obj->subtotal,
                'keterangan' => $obj->keterangan,
            ];
        }
    }

    #[Computed(persist: true)]
    public function optionsInputProdukId()
    {
        return SH_Produk::stokCabangWithStok(false);
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
    }

    public function addItem()
    {
        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_expired_date' => [],
            'input_no_batch' => [],
            'input_jumlah' => ['required', 'min:1', 'numeric'],
            'input_harga_satuan' => ['required', 'min:1', 'numeric'],
            'input_keterangan' => [],
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
            'keterangan' => $this->input_keterangan,
        ];

        $this->reset('input_produk_id', 'input_satuan_id', 'input_jumlah', 'input_expired_date', 'input_no_batch', 'input_keterangan');
        $this->updatedInputProdukId();
    }

    public function editItem()
    {
        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_expired_date' => [],
            'input_no_batch' => [],
            'input_jumlah' => ['required', 'min:1', 'numeric'],
            'input_harga_satuan' => ['required', 'min:1', 'numeric'],
            'input_keterangan' => [],
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
            'keterangan' => $this->input_keterangan,
        ];

        $this->reset(
            'input_produk_id',
            'input_satuan_id',
            'input_jumlah',
            'input_harga_satuan',
            'input_expired_date',
            'input_no_batch',
            'index_edit_item',
            'input_keterangan',
        );
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
        $this->input_keterangan = $item['keterangan'];

        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit()
    {
        $validated = $this->validate();
        try {
            DB::beginTransaction();
            StokAwalService::update($validated);
            session()->flash('flash_success', $this->menuTitle . ' berhasil diupdate.');
            DB::commit();

            return to_route('admin.system.setting.stok-awal');
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.system.setting.stok-awal')
            ->layout('admin.components.layouts.app');
    }
}
