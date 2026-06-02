<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualanViaSj;

use App\Models\Setting;
use Livewire\Component;
use App\Models\Master\Beban;
use App\Models\Master\Cabang;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Customer;
use Livewire\Attributes\Computed;
use App\Models\Master\ProdukSatuan;
use App\Models\Penjualan\SuratJalan;
use App\Traits\Livewire\WithCreateForm;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\FakturPenjualan;
use App\Utilities\Constants\Const_Setting;
use App\Utilities\Functions\TransactionFunction;
use App\Utilities\SelectHelpers\Master\SH_Beban;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Services\Penjualan\FakturPenjualanService;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_SuratJalan;

class Create extends Component
{
    use WithCreateForm;

    public $model = FakturPenjualan::class;
    public $menuTitle = 'Faktur Penjualan Via SJ';
    protected $page_permissions = ['admin.penjualan.faktur-penjualan-via-sj.create'];
    public $cabang_id;
    public $kode;
    public $jenis_transaksi = Const_Umum::JENIS_TRANSAKSI_FAKTUR_PENJUALAN_SJ;
    public $surat_jalan_id;
    public $tanggal;
    public $tanggal_jatuh_tempo;
    public $customer_id;
    public $customer_nama;
    public $gudang_id;
    public $keterangan;
    public $telp;
    public $handphone;
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
    public $input_diskon_satuan_type_1 = Const_Umum::DISKON_TYPE_RP;
    public $input_diskon_satuan_1;
    public $input_diskon_satuan_type_2 = Const_Umum::DISKON_TYPE_RP;
    public $input_diskon_satuan_2;
    public $input_diskon_satuan_type_3 = Const_Umum::DISKON_TYPE_RP;
    public $input_diskon_satuan_3;
    public $input_diskon_satuan_type_4 = Const_Umum::DISKON_TYPE_RP;
    public $input_diskon_satuan_4;
    public $index_edit_item = null;
    public $items_beban = [];
    public $input_beban_beban_id;
    public $input_beban_jumlah;
    public $index_edit_item_beban = null;
    public $total = 0;
    public $total_dpp = 0;
    public $total_ppn = 0;
    public $diskon_type = Const_Umum::DISKON_TYPE_RP;
    public $diskon = 0;
    public $total_beban = 0;
    public $grandtotal = 0;
    protected $listeners = [
        'setUpdated' => 'setUpdated',
        'refreshDataCustomer',
        'refreshDataProduk',
        'submitDefault',
        'selisihPpnUpdated',
    ];

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'jenis_transaksi' => ['required'],
            'surat_jalan_id' => ['required'],
            'tanggal' => ['required'],
            'tanggal_jatuh_tempo' => [],
            'customer_id' => ['required'],
            'gudang_id' => [],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'diskon_type' => ['required'],
            'diskon' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array',],
            'items.*.pesanan_penjualan_detail_id' => [],
            'items.*.surat_jalan_detail_id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type_1' => [],
            'items.*.diskon_satuan_1' => [],
            'items.*.diskon_satuan_type_2' => [],
            'items.*.diskon_satuan_2' => [],
            'items.*.diskon_satuan_type_3' => [],
            'items.*.diskon_satuan_3' => [],
            'items.*.diskon_satuan_type_4' => [],
            'items.*.diskon_satuan_4' => [],

            'items_beban' => ['nullable', 'array'],
            'items_beban.*.beban_id' => [],
            'items_beban.*.jumlah' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->tanggal = _get_default_datetime();
        $this->cabang_id = session()->get('cabang_id');
        $this->is_include_ppn = Cabang::find($this->cabang_id)?->is_include_ppn;
        $this->is_pkp = Cabang::find($this->cabang_id)?->is_pkp;
        $this->ppn_percent = Setting::fetch(Const_Setting::PPN_PERCENT) ?? 0;
    }

    #[Computed(persist: true)]
    public function optionsSuratJalanId()
    {
        return SH_SuratJalan::belumSelesai();
    }

    #[Computed(persist: true)]
    public function optionsInputProdukId()
    {
        return SH_Produk::stokCabangWithStok(false);
    }

    #[Computed(persist: true)]
    public function optionsInputBebanBebanId()
    {
        return SH_Beban::active(false);
    }

    public function updatedSuratJalanId()
    {
        $this->reset(
            'customer_id',
            'customer_nama',
            'tanggal_jatuh_tempo',
            'gudang_id',
            'keterangan',
            'telp',
            'handphone',
            'alamat',
            'kota',
            'kode_pos',
            'provinsi',
            'items',
            'items_beban',
        );
        if ($this->surat_jalan_id) {
            $suratJalan = SuratJalan::find($this->surat_jalan_id);
            $customer = $suratJalan->customer;
            $this->customer_id = optional($customer)->id;
            $this->customer_nama = optional($customer)->nama;
            $this->tanggal_jatuh_tempo = _datetime_carbon_db($this->tanggal)->addDays($customer?->jatuh_tempo ?? 0)->format(Const_Date::DATETIME_FORMAT_OUTPUT);
            $this->telp = optional($customer)->telp;
            $this->handphone = optional($customer)->handphone;
            $this->alamat = optional($customer)->alamat;
            $this->kota = optional($customer)->kota;
            $this->kode_pos = optional($customer)->kode_pos;
            $this->provinsi = optional($customer)->provinsi;

            $suratJalanDetail = $suratJalan->details()->with(['produk.modelProduk', 'satuan', 'pesananPenjualanDetail'])->get();

            foreach ($suratJalanDetail as $detail) {
                $produk = $detail->produk;
                $satuan = $detail->satuan;
                $pesananPenjualanDetail = $detail->pesananPenjualanDetail;
                $this->items[] = [
                    'pesanan_penjualan_id' => $suratJalan->pesanan_penjualan_id,
                    'pesanan_penjualan_detail_id' => $detail->pesanan_penjualan_detail_id,
                    'surat_jalan_detail_id' => $detail->id,
                    'produk_id' => $produk->id,
                    'produk_nama' => $produk->nama,
                    'satuan_id' => $satuan->id,
                    'satuan_nama' => $satuan->nama,
                    'model_produk_nama' => $produk->modelProduk?->nama,
                    'jumlah' => $detail->jumlah,
                    'harga_satuan' => $pesananPenjualanDetail->harga_satuan,
                    'diskon_satuan' => $pesananPenjualanDetail->diskon_satuan,
                    'diskon_satuan_type' => $pesananPenjualanDetail->diskon_satuan_type,
                    'keterangan' => $pesananPenjualanDetail->keterangan,
                ];
            }
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

        $this->input_satuan_id = $produk->default_satuan_jual_id;
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
            'input_jumlah' => ['required', 'numeric', 'min:1'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan_type_1' => [],
            'input_diskon_satuan_1' => [],
            'input_diskon_satuan_type_2' => [],
            'input_diskon_satuan_2' => [],
            'input_diskon_satuan_type_3' => [],
            'input_diskon_satuan_3' => [],
            'input_diskon_satuan_type_4' => [],
            'input_diskon_satuan_4' => [],
        ]);

        if ((!$this->input_diskon_satuan_3 && $this->input_diskon_satuan_4)
            || (!$this->input_diskon_satuan_2 && $this->input_diskon_satuan_3)
            || (!$this->input_diskon_satuan_1 && $this->input_diskon_satuan_2)
        ) {
            $this->addError('flash_danger', 'Harap mengisi input diskon dengan urut.');
            $this->dispatch('page-to-top');
            return;
        }

        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;
        $diskon_satuan_type_1 = $this->input_diskon_satuan_1 ? $this->input_diskon_satuan_type_1 : null;
        $diskon_satuan_1 = $this->input_diskon_satuan_1 ?: 0;
        $diskon_satuan_type_2 = $this->input_diskon_satuan_2 ? $this->input_diskon_satuan_type_2 : null;
        $diskon_satuan_2 = $this->input_diskon_satuan_2 ?: 0;
        $diskon_satuan_type_3 = $this->input_diskon_satuan_3 ? $this->input_diskon_satuan_type_3 : null;
        $diskon_satuan_3 = $this->input_diskon_satuan_3 ?: 0;
        $diskon_satuan_type_4 = $this->input_diskon_satuan_4 ? $this->input_diskon_satuan_type_4 : null;
        $diskon_satuan_4 = $this->input_diskon_satuan_4 ?: 0;

        $this->items[] = [
            'pesanan_penjualan_id' => null,
            'pesanan_penjualan_detail_id' => null,
            'surat_jalan_detail_id' => null,
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan_1' => $diskon_satuan_1,
            'diskon_satuan_type_1' => $diskon_satuan_type_1,
            'diskon_satuan_2' => $diskon_satuan_2,
            'diskon_satuan_type_2' => $diskon_satuan_type_2,
            'diskon_satuan_3' => $diskon_satuan_3,
            'diskon_satuan_type_3' => $diskon_satuan_type_3,
            'diskon_satuan_4' => $diskon_satuan_4,
            'diskon_satuan_type_4' => $diskon_satuan_type_4,
        ];

        $this->reset(
            'input_produk_id',
            'input_satuan_id',
            'input_jumlah',
            'input_harga_satuan',
            'input_diskon_satuan_type_1',
            'input_diskon_satuan_1',
            'input_diskon_satuan_type_2',
            'input_diskon_satuan_2',
            'input_diskon_satuan_type_3',
            'input_diskon_satuan_3',
            'input_diskon_satuan_type_4',
            'input_diskon_satuan_4',
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
            'input_jumlah' => ['required', 'numeric', 'min:1'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan_type_1' => [],
            'input_diskon_satuan_1' => [],
            'input_diskon_satuan_type_2' => [],
            'input_diskon_satuan_2' => [],
            'input_diskon_satuan_type_3' => [],
            'input_diskon_satuan_3' => [],
            'input_diskon_satuan_type_4' => [],
            'input_diskon_satuan_4' => [],
        ]);

        if ((!$this->input_diskon_satuan_3 && $this->input_diskon_satuan_4)
            || (!$this->input_diskon_satuan_2 && $this->input_diskon_satuan_3)
            || (!$this->input_diskon_satuan_1 && $this->input_diskon_satuan_2)
        ) {
            $this->addError('flash_danger', 'Harap mengisi input diskon dengan urut.');
            $this->dispatch('page-to-top');
            return;
        }

        $produk = Produk::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;
        $diskon_satuan_type_1 = $this->input_diskon_satuan_1 ? $this->input_diskon_satuan_type_1 : null;
        $diskon_satuan_1 = $this->input_diskon_satuan_1 ?: 0;
        $diskon_satuan_type_2 = $this->input_diskon_satuan_2 ? $this->input_diskon_satuan_type_2 : null;
        $diskon_satuan_2 = $this->input_diskon_satuan_2 ?: 0;
        $diskon_satuan_type_3 = $this->input_diskon_satuan_3 ? $this->input_diskon_satuan_type_3 : null;
        $diskon_satuan_3 = $this->input_diskon_satuan_3 ?: 0;
        $diskon_satuan_type_4 = $this->input_diskon_satuan_4 ? $this->input_diskon_satuan_type_4 : null;
        $diskon_satuan_4 = $this->input_diskon_satuan_4 ?: 0;

        $this->items[$this->index_edit_item] = [
            'pesanan_penjualan_id' => $this->items[$this->index_edit_item]['pesanan_penjualan_id'],
            'pesanan_penjualan_detail_id' => $this->items[$this->index_edit_item]['pesanan_penjualan_detail_id'],
            'surat_jalan_detail_id' => $this->items[$this->index_edit_item]['surat_jalan_detail_id'],
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan_1' => $diskon_satuan_1,
            'diskon_satuan_type_1' => $diskon_satuan_type_1,
            'diskon_satuan_2' => $diskon_satuan_2,
            'diskon_satuan_type_2' => $diskon_satuan_type_2,
            'diskon_satuan_3' => $diskon_satuan_3,
            'diskon_satuan_type_3' => $diskon_satuan_type_3,
            'diskon_satuan_4' => $diskon_satuan_4,
            'diskon_satuan_type_4' => $diskon_satuan_type_4,
        ];

        $this->reset(
            'input_produk_id',
            'input_satuan_id',
            'input_jumlah',
            'input_harga_satuan',
            'input_diskon_satuan_type_1',
            'input_diskon_satuan_1',
            'input_diskon_satuan_type_2',
            'input_diskon_satuan_2',
            'input_diskon_satuan_type_3',
            'input_diskon_satuan_3',
            'input_diskon_satuan_type_4',
            'input_diskon_satuan_4',
            'index_edit_item',
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
        $this->input_diskon_satuan_type_1 = $item['diskon_satuan_type_1'] ?: Const_Umum::DISKON_TYPE_RP;
        $this->input_diskon_satuan_1 = $item['diskon_satuan_1'];
        $this->input_diskon_satuan_type_2 = $item['diskon_satuan_type_2'] ?: Const_Umum::DISKON_TYPE_RP;
        $this->input_diskon_satuan_2 = $item['diskon_satuan_2'];
        $this->input_diskon_satuan_type_3 = $item['diskon_satuan_type_3'] ?: Const_Umum::DISKON_TYPE_RP;
        $this->input_diskon_satuan_3 = $item['diskon_satuan_3'];
        $this->input_diskon_satuan_type_4 = $item['diskon_satuan_type_4'] ?: Const_Umum::DISKON_TYPE_RP;
        $this->input_diskon_satuan_4 = $item['diskon_satuan_4'];

        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addItemBeban()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_beban_beban_id' => ['required'],
            'input_beban_jumlah' => ['required'],
        ]);
        $beban = Beban::find($this->input_beban_beban_id);
        $jumlah = $this->input_beban_jumlah;

        $this->items_beban[] = [
            'beban_id' => $beban->id,
            'beban_nama' => $beban->nama,
            'jumlah' => $jumlah,
        ];

        $this->reset('input_beban_beban_id', 'input_beban_jumlah');
    }

    public function editItemBeban()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_beban_beban_id' => ['required'],
            'input_beban_jumlah' => ['required'],
        ]);
        $beban = Beban::find($this->input_beban_beban_id);
        $jumlah = $this->input_beban_jumlah;

        $this->items_beban[$this->index_edit_item_beban] = [
            'beban_id' => $beban->id,
            'beban_nama' => $beban->nama,
            'jumlah' => $jumlah,
        ];

        $this->reset('input_beban_beban_id', 'input_beban_jumlah', 'index_edit_item_beban');
    }

    public function editBeban($index)
    {
        $this->index_edit_item_beban = $index;

        $item = $this->items_beban[$index];
        $this->input_beban_beban_id = $item['beban_id'];
        $this->input_beban_jumlah = $item['jumlah'];
    }

    public function removeItemBeban($index)
    {
        unset($this->items_beban[$index]);
        $this->items_beban = array_values($this->items_beban);
    }

    public function confirmation($validated): bool
    {
        if ($validated['customer_id']) {
            $customer = Customer::find($validated['customer_id']);
            $totalPiutang = TransactionFunction::getPiutangCustomer($customer->id) + $this->grandtotal;
            $limitPiutang = $customer?->limit_piutang;

            if ($totalPiutang > $limitPiutang && $limitPiutang != 0) {
                $this->confirmationMessage = 'Piutang Customer ' . $customer->nama . ' melebihi limit yang ditetapkan ' . _number($limitPiutang) . ' < ' . _number($totalPiutang) . '. Apakah Anda tetap ingin melanjutkan transaksi ini?';
                return false;
            }
        }

        return true;
    }

    public function submit($validated)
    {
        return FakturPenjualanService::create($validated);
    }

    public function render()
    {
        $this->calculateFooter();

        return view('admin.penjualan.faktur-penjualan-via-sj.create')->layout($this->layout);
    }
}
