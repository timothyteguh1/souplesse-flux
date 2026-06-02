<?php

namespace App\Livewire\Admin\Penjualan\PesananPenjualan;

use App\Models\Setting;
use Livewire\Component;
use App\Models\Master\Cabang;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Customer;
use Livewire\Attributes\Computed;
use App\Models\Master\ProdukSatuan;
use App\Traits\Livewire\WithCreateForm;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\PesananPenjualan;
use App\Utilities\Constants\Const_Setting;
use App\Utilities\Constants\Const_JenisTransaksi;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Services\Penjualan\PesananPenjualanService;
use App\Utilities\SelectHelpers\Master\SH_Customer;
use App\Models\Master\Beban;
use App\Utilities\SelectHelpers\Master\SH_Beban;
use App\Utilities\SelectHelpers\Master\SH_Karyawan;

class Create extends Component
{
    use WithCreateForm;

    public $model = PesananPenjualan::class;
    public $menuTitle = 'Pesanan Penjualan';
    public $cabang_id;
    public $jenis_transaksi = Const_JenisTransaksi::PESANAN_PENJUALAN;
    public $kode;
    public $tanggal;
    public $karyawan_id;
    public $customer_id;
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
    public $items_beban = [];
    public $input_beban_beban_id;
    public $input_beban_jumlah;
    public $index_edit_item_beban = null;
    public $total = 0;
    public $total_dpp = 0;
    public $total_ppn = 0;
    public $diskon_type = Const_Umum::DISKON_TYPE_PERCENT;
    public $diskon = 0;
    public $total_beban = 0;
    public $grandtotal = 0;
    protected $listeners = [
        'refreshDataCustomer',
    ];

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'jenis_transaksi' => ['required'],
            'kode' => [],
            'tanggal' => ['required'],
            'customer_id' => ['required'],
            'karyawan_id' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'diskon_type' => ['required'],
            'diskon' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],
            'items.*.keterangan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();

        $this->tanggal = _get_default_datetime();
        $this->cabang_id = session()->get('cabang_id');
        $this->ppn_percent = Setting::fetch(Const_Setting::PPN_PERCENT) ?? 0;
    }

    #[Computed(persist: true)]
    public function optionsKaryawanId()
    {
        return SH_Karyawan::active();
    }

    #[Computed(persist: true)]
    public function optionsCustomerId()
    {
        return SH_Customer::active();
    }

    #[Computed(persist: true)]
    public function optionsInputProdukId()
    {
        return SH_Produk::stokCabangWithStok(false);
    }

    public function refreshDataCustomer($params)
    {
        $new_id = $params['new_id'];

        $options = SH_Customer::active();
        $this->dispatch('refresh_dropdown_customer_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->customer_id = $new_id;
        $this->dispatch('set_value_dropdown_customer_id', $this->customer_id);
        $this->updatedCustomerId();
    }

    public function updatedCustomerId()
    {
        $customer = Customer::find($this->customer_id);
        $this->reset(['alamat', 'kota', 'kode_pos', 'provinsi', 'is_pkp', 'is_include_ppn']);

        if ($customer) {
            $this->alamat = optional($customer)->alamat;
            $this->kota = optional($customer)->kota;
            $this->kode_pos = optional($customer)->kode_pos;
            $this->provinsi = optional($customer)->provinsi;
            $this->is_include_ppn = Cabang::find($this->cabang_id)?->is_include_ppn;
            $this->is_pkp = Cabang::find($this->cabang_id)?->is_pkp;
            $this->ppn_percent = Setting::fetch(Const_Setting::PPN_PERCENT) ?? 0;
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

        $this->input_harga_satuan = $produkSatuan?->harga_jual ?? 0;
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
                if ($diskon_satuan_type == Const_Umum::DISKON_TYPE_PERCENT) {
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

        // hitung harga net satuan per item, dengan tambahan diskon dan biaya footer
        $diskon = $this->diskon ?: 0;
        $diskon_type = $this->diskon_type;
        $this->total_beban = collect($this->items_beban)->sum('jumlah');

        $diskon_rupiah = 0;
        if ($diskon > 0) {
            if ($diskon_type == Const_Umum::DISKON_TYPE_PERCENT) {
                $diskon_rupiah = $diskon;
            }
            if ($diskon_type == Const_Umum::DISKON_TYPE_PERCENT) {
                $diskon_rupiah = $total * $diskon / 100;
            }
        }

        $dpp = _round($total - $diskon_rupiah, 0);
        $ppn = 0;
        if ($this->is_pkp) {
            if ($this->is_include_ppn) {
                $dpp = _round(($total - $diskon_rupiah) / (1 + $this->ppn_percent / 100), 0);
            }

            $ppn = _round($dpp * $this->ppn_percent / 100, 0);
        }

        foreach ($this->items as $index => $item) {
            $jumlah = $item['jumlah'];
            $harga_net_satuan = $item['harga_net_satuan'];
            $subtotal = $item['subtotal'];

            $dpp_satuan = _round($dpp / $total * $subtotal / $jumlah);
            $ppn_satuan = $dpp == 0 ? 0 : _round($dpp_satuan / $dpp * $ppn);
            $diskon_satuan_footer = $dpp == 0 ? 0 : _round($dpp_satuan / $dpp * $diskon_rupiah);
            $harga_net_satuan_akhir = $harga_net_satuan - $diskon_satuan_footer;

            $this->items[$index]['diskon_satuan_footer'] = $diskon_satuan_footer;
            $this->items[$index]['harga_net_satuan_akhir'] = $harga_net_satuan_akhir;
            $this->items[$index]['ppn_satuan'] = $ppn_satuan;
            $this->items[$index]['dpp_satuan'] = $dpp_satuan;
        }

        $this->total = $total;
        $this->total_dpp = $dpp;
        $this->total_ppn = $ppn;
        $this->grandtotal = $dpp + $ppn + $this->total_beban;
    }

    public function addItem()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
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

    public function editItem()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
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
        return PesananPenjualanService::create($validated);
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.penjualan.pesanan-penjualan.create')->layout($this->layout);
    }
}
