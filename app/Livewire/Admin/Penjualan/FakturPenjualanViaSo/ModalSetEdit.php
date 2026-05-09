<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualanViaSo;

use Exception;
use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\ProdukSatuan;
use App\Traits\Livewire\WithModalForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\SelectHelpers\Master\SH_Produk;

class ModalSetEdit extends Component
{
    use WithModalForm;

    public $index_modal_set;
    public $gudang_id;
    public $id;
    public $produk_teks;
    public $satuan_teks;
    public $jumlah;
    public $total = 0;
    public $diskon_type = Const_Umum::DISKON_TYPE_VALUE;
    public $diskon = 0;
    public $biaya_lain = 0;
    public $grandtotal = 0;
    public $items = [];
    public $input_set_produk_id;
    public $input_set_satuan_id;
    public $input_set_jumlah;
    public $input_set_harga_satuan;
    public $input_set_diskon_satuan_type = Const_Umum::DISKON_TYPE_VALUE;
    public $input_set_diskon_satuan;
    public $index_edit_item = null;
    protected $listeners = [
        'refreshInfo' => 'refreshInfo',
    ];

    protected function rules(): array
    {
        return [
            'id' => [],
            'gudang_id' => ['required'],
            'produk_teks' => [],
            'satuan_teks' => ['required'],
            'jumlah' => ['required'],
            'diskon_type' => ['required'],
            'diskon' => ['required'],
            'biaya_lain' => ['required'],
            'grandtotal' => ['required'],

            'items' => ['required', 'array'],
            'items.*.id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],
            'items.*.produk_kode' => [],
            'items.*.produk_nama' => [],
            'items.*.satuan_nama' => [],
            'items.*.subtotal' => [],
        ];
    }

    public function refreshInfo($params)
    {
        $this->gudang_id = $params['gudang_id'];
        $this->index_modal_set = $params['index'];

        $this->reset(
            'id',
            'produk_teks',
            'satuan_teks',
            'jumlah',
            'diskon',
            'biaya_lain',
            'input_set_produk_id',
            'input_set_satuan_id',
            'input_set_jumlah',
            'input_set_harga_satuan',
            'input_set_diskon_satuan_type',
            'input_set_diskon_satuan',
            'items',
        );

        if ($this->index_modal_set !== null) {
            $this->id = $params['items']['id'];
            $this->produk_teks = $params['items']['produk_teks'];
            $this->satuan_teks = $params['items']['satuan_teks'];
            $this->jumlah = $params['items']['jumlah'];
            $this->diskon = $params['items']['diskon'];
            $this->diskon_type = $params['items']['diskon_type'];
            $this->biaya_lain = $params['items']['biaya_lain'];

            foreach ($params['items']['items'] as $item) {
                $this->items[] = [
                    'id' => $item['id'],
                    'produk_id' => $item['produk_id'],
                    'satuan_id' => $item['satuan_id'],
                    'jumlah' => $item['jumlah'],
                    'harga_satuan' => $item['harga_satuan'],
                    'diskon_satuan' => $item['diskon_satuan'],
                    'diskon_satuan_type' => $item['diskon_satuan_type'],

                    'produk_kode' => $item['produk_kode'],
                    'produk_nama' => $item['produk_nama'],
                    'satuan_nama' => $item['satuan_nama'],
                ];
            }
        }

        $options = SH_Produk::stokGudangWithStok($this->gudang_id, false);
        $this->dispatch('refresh_dropdown_input_set_produk_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->showModal();
    }

    public function updatedInputSetProdukId()
    {
        $produk = Produk::find($this->input_set_produk_id);

        if (!$produk) {
            $this->dispatch('refresh_dropdown_input_set_satuan_id', [
                'options' => [],
                'value' => null,
            ]);

            $this->input_set_satuan_id = null;
            $this->dispatch('set_value_dropdown_input_set_satuan_id', $this->input_set_satuan_id);
            $this->updatedInputSetSatuanId();
            return;
        }

        $options = SH_Produk::satuansStokGudang($produk->id, $this->gudang_id);
        $this->dispatch('refresh_dropdown_input_set_satuan_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->input_set_satuan_id = $produk->default_satuan_jual_id;
        $this->dispatch('set_value_dropdown_input_set_satuan_id', $this->input_set_satuan_id);
        $this->updatedInputSetSatuanId();
    }

    public function updatedInputSetSatuanId()
    {
        $produk = Produk::find($this->input_set_produk_id);
        $satuan = Satuan::find($this->input_set_satuan_id);

        if (!$produk || !$satuan) {
            return;
        }

        $produkSatuan = ProdukSatuan::query()
            ->where('produk_id', $produk->id)
            ->where('satuan_id', $satuan->id)
            ->first();

        $this->input_set_harga_satuan = $produkSatuan?->harga_jual_bawah ?? 0;
    }

    public function addItem()
    {
        $this->validate([
            'input_set_produk_id' => ['required'],
            'input_set_satuan_id' => ['required'],
            'input_set_jumlah' => ['required'],
            'input_set_harga_satuan' => ['required'],
            'input_set_diskon_satuan_type' => [],
            'input_set_diskon_satuan' => [],
        ]);

        $produk = Produk::find($this->input_set_produk_id);
        $satuan = Satuan::find($this->input_set_satuan_id);
        $diskon_satuan_type = $this->input_set_diskon_satuan ? $this->input_set_diskon_satuan_type : null;
        $diskon_satuan = $this->input_set_diskon_satuan ?: 0;
        $jumlah = $this->input_set_jumlah;
        $harga_satuan = $this->input_set_harga_satuan;

        $this->items[] = [
            'id' => null,
            'produk_id' => $produk->id,
            'satuan_id' => $satuan->id,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,

            'produk_kode' => $produk->kode,
            'produk_nama' => $produk->nama,
            'satuan_nama' => $satuan->nama,
        ];

        $this->reset(
            'input_set_produk_id',
            'input_set_satuan_id',
            'input_set_jumlah',
            'input_set_harga_satuan',
            'input_set_diskon_satuan',
        );
        $this->updatedInputSetProdukId();
    }

    public function editItem()
    {
        $this->validate([
            'input_set_produk_id' => ['required'],
            'input_set_satuan_id' => ['required'],
            'input_set_jumlah' => ['required'],
            'input_set_harga_satuan' => ['required'],
            'input_set_diskon_satuan_type' => [],
            'input_set_diskon_satuan' => [],
        ]);

        $produk = Produk::find($this->input_set_produk_id);
        $satuan = Satuan::find($this->input_set_satuan_id);
        $diskon_satuan_type = $this->input_set_diskon_satuan ? $this->input_set_diskon_satuan_type : null;
        $diskon_satuan = $this->input_set_diskon_satuan ?: 0;
        $jumlah = $this->input_set_jumlah;
        $harga_satuan = $this->input_set_harga_satuan;

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'produk_id' => $produk->id,
            'satuan_id' => $satuan->id,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,

            'produk_kode' => $produk->kode,
            'produk_nama' => $produk->nama,
            'satuan_nama' => $satuan->nama,
        ];

        $this->reset(
            'input_set_produk_id',
            'input_set_satuan_id',
            'input_set_jumlah',
            'input_set_harga_satuan',
            'input_set_diskon_satuan',
            'index_edit_item',
        );
        $this->updatedInputSetProdukId();
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_set_produk_id = $item['produk_id'];
        $this->updatedInputSetProdukId();

        $this->input_set_satuan_id = $item['satuan_id'];
        $this->input_set_jumlah = $item['jumlah'];
        $this->input_set_harga_satuan = $item['harga_satuan'];
        $this->input_set_diskon_satuan_type = $item['diskon_satuan_type'] ?: Const_Umum::DISKON_TYPE_VALUE;
        $this->input_set_diskon_satuan = $item['diskon_satuan'];

        $this->dispatch('set_value_dropdown_input_set_satuan_id', $this->input_set_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateFooter()
    {
        // hitung harga net satuan per item, diskon dan biaya footer tidak di hitung
        foreach ($this->items as $index => $item) {
            $jumlah = $item['jumlah'];
            $harga_satuan = $item['harga_satuan'];
            $diskon_satuan = $item['diskon_satuan'];
            $diskon_satuan_type = $item['diskon_satuan_type'];

            $diskon_satuan_persen = 0;
            $diskon_satuan_rupiah = 0;
            if ($diskon_satuan > 0) {
                if ($diskon_satuan_type == Const_Umum::DISKON_TYPE_VALUE) {
                    $diskon_satuan_rupiah = $diskon_satuan;
                    $diskon_satuan_persen = $harga_satuan != 0 ? $diskon_satuan_rupiah * 100 / $harga_satuan : 0;
                }
                if ($diskon_satuan_type == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskon_satuan_persen = $diskon_satuan;
                    $diskon_satuan_rupiah = $harga_satuan * $diskon_satuan_persen / 100;
                }
            }

            $harga_net_satuan = $harga_satuan - $diskon_satuan_rupiah;
            $subtotal = $harga_net_satuan * $jumlah;

            $this->items[$index]['diskon_satuan_persen'] = $diskon_satuan_persen;
            $this->items[$index]['diskon_satuan_rupiah'] = $diskon_satuan_rupiah;
            $this->items[$index]['harga_net_satuan'] = $harga_net_satuan;
            $this->items[$index]['subtotal'] = $subtotal;
        }

        $total = collect($this->items)->sum(function ($item) {
            return $item['subtotal'];
        });
        if ($this->jumlah) {
            $total *= $this->jumlah;
        }
        $total = _round($total);

        // hitung harga net satuan per item, dengan tambahan diskon dan biaya footer
        $diskon = $this->diskon ?: 0;
        $diskon_type = $this->diskon_type;

        $diskon_rupiah = 0;
        if ($diskon > 0) {
            if ($diskon_type == Const_Umum::DISKON_TYPE_VALUE) {
                $diskon_rupiah = $diskon;
            }
            if ($diskon_type == Const_Umum::DISKON_TYPE_PERCENT) {
                $diskon_rupiah = $total * $diskon / 100;
            }
        }

        foreach ($this->items as $index => $item) {
            $jumlah = $item['jumlah'];
            $harga_net_satuan = $item['harga_net_satuan'];
            $subtotal = $item['subtotal'];

            $diskon_satuan_footer = $diskon_rupiah == 0 ? 0 : $diskon_rupiah / collect($this->items)->sum('jumlah') / $this->jumlah;
            $harga_net_satuan_akhir = $harga_net_satuan - $diskon_satuan_footer;

            $this->items[$index]['diskon_satuan_footer'] = $diskon_satuan_footer;
            $this->items[$index]['harga_net_satuan_akhir'] = $harga_net_satuan_akhir;
        }

        $this->total = $total;
        $this->grandtotal = $total - $diskon_rupiah;
    }

    public function submit()
    {
        $validated = $this->validate();
        $data['index'] = $this->index_modal_set;
        $data['items'] = $validated;
        try {
            $this->dispatch('setUpdated', $data);
            $this->closeModal();
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.penjualan.faktur-penjualan-via-so.modal-set-edit');
    }
}
