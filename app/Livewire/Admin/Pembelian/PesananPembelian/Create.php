<?php

namespace App\Livewire\Admin\Pembelian\PesananPembelian;

use App\Models\Master\Produk;
use App\Models\Master\ProdukSatuan;
use App\Models\Master\Satuan;
use App\Models\Master\Supplier;
use App\Models\Pembelian\PesananPembelian;
use App\Models\Setting;
use App\Services\Pembelian\PesananPembelianService;
use App\Traits\Livewire\WithCreateForm;
use App\Utilities\Constants\Const_Setting;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use Livewire\Component;

class Create extends Component
{
    use WithCreateForm;

    public $model = PesananPembelian::class;
    public $menuTitle = 'PO Pembelian';
    public $cabang_id;
    public $kode;
    public $tanggal;
    public $supplier_id;
    public $keterangan;
    public $alamat;
    public $kota;
    public $kode_pos;
    public $provinsi;
    public bool $is_pkp = false;
    public bool $is_include_ppn = false;
    public $ppn_percent;
    public $items = [];
    public $input_produk_id;
    public $input_satuan_id;
    public $input_jumlah;
    public $input_harga_satuan;
    public $input_diskon_satuan_type = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan;
    public $input_keterangan;
    public $index_edit_item = null;
    public $total = 0;
    public $total_dpp = 0;
    public $total_ppn = 0;
    public $diskon_type = Const_Umum::DISKON_TYPE_PERCENT;
    public $diskon = 0;
    public $pembulatan_rupiah = 0;
    public $total_setelah_pembulatan = 0;
    public $grandtotal = 0;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'tanggal' => ['required'],
            'supplier_id' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],
            'items.*.keterangan' => [],

            'diskon_type' => ['required'],
            'diskon' => ['required'],
            // 'pembulatan_rupiah' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();

        $this->tanggal = _get_default_datetime();
        $this->cabang_id = session()->get('cabang_id');
        $this->ppn_percent = Setting::fetch(Const_Setting::PPN_PERCENT) ?? 0;

        $countSupplier = Supplier::count();
        if ($countSupplier == 1) {
            $this->supplier_id = Supplier::first()?->id;
            $this->updatedSupplierId();
        }
    }

    public function updatedSupplierId()
    {
        $supplier = Supplier::find($this->supplier_id);
        $this->reset('alamat', 'kota', 'kode_pos', 'provinsi', 'is_pkp', 'is_include_ppn', 'input_produk_id');

        if ($supplier) {
            $this->alamat = $supplier?->alamat;
            $this->kota = $supplier?->kota;
            $this->kode_pos = $supplier?->kode_pos;
            $this->provinsi = $supplier?->provinsi;
            $this->is_pkp = $supplier?->is_pkp;
            $this->is_include_ppn = $supplier?->is_include_ppn;

            $options = SH_Produk::stokCabangWithStok(false);
            $this->dispatch('refresh_dropdown_input_produk_id', [
                'options' => $options,
                'value' => null,
            ]);
        }
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

        $options = SH_Produk::satuansStokCabang($produk->id);
        $this->dispatch('refresh_dropdown_input_satuan_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->input_satuan_id = $produk->default_satuan_beli_id;
        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
        $satuanPcs = Satuan::where('nama', 'PCS')->first();
        $this->input_satuan_id = $satuanPcs->id;
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

        $produkSatuan = Produk::query()
            ->where('id', $produk->id)
            ->where('satuan_id', $satuan->id)
            ->first();

        $this->input_harga_satuan = $produkSatuan?->harga_beli ?? 0;
    }

    public function calculateFooter()
    {
        // hitung harga net satuan per item, diskon dan beban footer tidak di hitung
        foreach ($this->items as $index => $item) {
            $jumlah = $item['jumlah'];
            $harga_satuan = $item['harga_satuan'];
            $diskon_satuan = $item['diskon_satuan'];
            $diskon_satuan_type = $item['diskon_satuan_type'];

            $diskon_satuan_persen = 0;
            $diskon_satuan_rupiah = 0;
            if ($diskon_satuan > 0) {
                if ($diskon_satuan_type == Const_Umum::DISKON_TYPE_RP) {
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
        $total = _round($total);

        // hitung harga net satuan per item, dengan tambahan diskon dan beban footer
        $diskon = $this->diskon ?: 0;
        $diskon_type = $this->diskon_type;

        $diskon_rupiah = 0;
        if ($diskon > 0) {
            if ($diskon_type == Const_Umum::DISKON_TYPE_RP) {
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
            $diskon_satuan_footer = ($subtotal * $diskon_rupiah / $total) / $jumlah;
            $harga_net_satuan_akhir = $harga_net_satuan - $diskon_satuan_footer;

            $ppn_satuan = 0;
            $dpp_satuan = 0;
            if ($this->is_pkp) {
                if ($this->is_include_ppn) {
                    $ppn_satuan = _ppn_value($harga_net_satuan_akhir, $this->ppn_percent, true);
                    $dpp_satuan = _round($harga_net_satuan_akhir - $ppn_satuan, 2);
                } else {
                    $ppn_satuan = _ppn_value($harga_net_satuan_akhir, $this->ppn_percent);
                    $dpp_satuan = _round($harga_net_satuan_akhir, 2);
                }
            }

            $this->items[$index]['diskon_satuan_footer'] = $diskon_satuan_footer;
            $this->items[$index]['harga_net_satuan_akhir'] = $harga_net_satuan_akhir;
            $this->items[$index]['ppn_satuan'] = $ppn_satuan;
            $this->items[$index]['dpp_satuan'] = $dpp_satuan;
        }

        // update footer
        $total_dpp = collect($this->items)->sum(function ($item) {
            return $item['dpp_satuan'] * $item['jumlah'];
        });
        $total_ppn = collect($this->items)->sum(function ($item) {
            return $item['ppn_satuan'] * $item['jumlah'];
        });

        $this->total = $total;
        $this->total_dpp = $total_dpp;
        $this->total_ppn = $total_ppn;
        $this->grandtotal = $total - $diskon_rupiah  + $total_ppn;
        if ($this->is_pkp && $this->is_include_ppn) {
            $this->grandtotal = $total_ppn + $total_dpp;
        }
    }

    public function addItem()
    {
        if (!$this->supplier_id) {
            $this->addError('flash_danger', 'Supplier harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan_type' => [],
            'input_diskon_satuan' => [],
            'input_keterangan' => [],
        ]);

        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $diskon_satuan_type = $this->input_diskon_satuan ? $this->input_diskon_satuan_type : null;
        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;
        $keterangan = $this->input_keterangan;

        $this->items[] = [
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'model_produk_nama' => $produk->modelProduk?->nama,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,
            'keterangan' => $keterangan,
        ];
        $this->resetDetail();
    }

    public function editItem()
    {
        if (!$this->supplier_id) {
            $this->addError('flash_danger', 'Supplier harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan_type' => [],
            'input_diskon_satuan' => [],
            'input_keterangan' => [],
        ]);

        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $diskon_satuan_type = $this->input_diskon_satuan ? $this->input_diskon_satuan_type : null;
        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;
        $keterangan = $this->input_keterangan;

        $this->items[$this->index_edit_item] = [
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'model_produk_nama' => $produk->modelProduk?->nama,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,
            'keterangan' => $keterangan,
        ];
        $this->resetDetail();
    }

    private function resetDetail()
    {
        $this->reset(
            'input_produk_id',
            'input_satuan_id',
            'input_jumlah',
            'input_harga_satuan',
            'input_diskon_satuan',
            'input_keterangan',
            'index_edit_item'
        );
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_produk_id = $item['produk_id'];
        $this->updatedInputProdukId();

        $this->input_satuan_id = $item['satuan_id'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_harga_satuan = $item['harga_satuan'];
        $this->input_diskon_satuan_type = $item['diskon_satuan_type'] ?: Const_Umum::DISKON_TYPE_PERCENT;
        $this->input_diskon_satuan = $item['diskon_satuan'];
        $this->input_keterangan = $item['keterangan'];

        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
        $this->resetDetail();
    }

    public function submit($validated)
    {
        return PesananPembelianService::create($validated);
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.pembelian.pesanan-pembelian.create')->layout($this->layout);
    }
}
