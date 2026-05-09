<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualanViaSj;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Customer;
use Livewire\Attributes\Computed;
use App\Traits\Livewire\WithEditForm;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\FakturPenjualan;
use App\Models\Penjualan\PerintahService;
use App\Models\Penjualan\SuratJalanDetail;
use App\Utilities\Functions\TransactionFunction;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use App\Services\Penjualan\FakturPenjualanService;
use App\Utilities\SelectHelpers\SH_JenisTransaksi;
use App\Utilities\SelectHelpers\Master\SH_Customer;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_SuratJalan;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_PerintahService;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_SuratJalanDetail;
use App\Models\Master\Beban;
use App\Utilities\SelectHelpers\Master\SH_Beban;
use App\Livewire\Admin\Master\Pajak\ModalSelisihPpn;

class Edit extends Component
{
    use WithEditForm;

    public $model = FakturPenjualan::class;
    public $menuTitle = 'Faktur Penjualan Via SJ';
    public FakturPenjualan $obj;
    public $kode;
    public $jenis_transaksi;
    public $tanggal;
    public $tanggal_jatuh_tempo;
    public $customer_id;
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
    public $ppn_selisih = 0;
    public $items = [];
    public $input_surat_jalan_id;
    public $input_produk_id;
    public $input_satuan_id;
    public $input_satuan_nama;
    public $input_jumlah;
    public $input_harga_satuan;
    public $input_diskon_satuan_type = Const_Umum::DISKON_TYPE_VALUE;
    public $input_diskon_satuan;
    public $index_edit_item = null;
    public $items_beban = [];
    public $input_beban_beban_id;
    public $input_beban_jumlah;
    public $index_edit_item_beban = null;
    public $total = 0;
    public $total_set = 0;
    public $total_service = 0;
    public $total_dpp = 0;
    public $total_ppn = 0;
    public $diskon_type = Const_Umum::DISKON_TYPE_VALUE;
    public $diskon = 0;
    public $total_beban = 0;
    public $grandtotal = 0;
    public $items_set = [];
    public $items_service = [];
    public $input_service_perintah_service_id = 0;
    public $index_edit_item_service = null;
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
            'jenis_transaksi' => ['required'],
            'tanggal' => ['required'],
            'tanggal_jatuh_tempo' => [],
            'customer_id' => ['required'],
            'gudang_id' => [],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'ppn_selisih' => [],
            'diskon_type' => ['required'],
            'diskon' => ['required'],
            'keterangan' => [],

            'items' => ['nullable', 'array', 'required_without_all:items_set,items_service'],
            'items.*.id' => [],
            'items.*.pesanan_penjualan_id' => [],
            'items.*.pesanan_penjualan_detail_id' => [],
            'items.*.surat_jalan_detail_id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],

            'items_set' => ['nullable', 'array', 'required_without_all:items,items_service'],
            'items_set.*.id' => [],
            'items_set.*.gudang_id' => [],
            'items_set.*.produk_teks' => [],
            'items_set.*.satuan_teks' => [],
            'items_set.*.jumlah' => [],
            'items_set.*.diskon_type' => [],
            'items_set.*.diskon' => [],
            'items_set.*.biaya_lain' => [],
            'items_set.*.items' => [],

            'items_service' => ['nullable', 'array', 'required_without_all:items_set,items'],
            'items_service.*.id' => [],
            'items_service.*.perintah_service_id' => [],

            'items_beban' => ['nullable', 'array'],
            'items_beban.*.id' => [],
            'items_beban.*.beban_id' => [],
            'items_beban.*.jumlah' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->jenis_transaksi = $this->obj->jenis_transaksi;
        $this->tanggal = $this->obj->tanggal;
        $this->tanggal_jatuh_tempo = $this->obj->tanggal_jatuh_tempo;
        $this->customer_id = $this->obj->customer_id;
        $this->gudang_id = $this->obj->gudang_id;
        $this->keterangan = $this->obj->keterangan;
        $this->handphone = $this->obj->customer->handphone;
        $this->telp = $this->obj->customer->telp;
        $this->alamat = $this->obj->customer->alamat;
        $this->kota = $this->obj->customer->kota;
        $this->kode_pos = $this->obj->customer->kode_pos;
        $this->provinsi = $this->obj->customer->provinsi;
        $this->is_pkp = $this->obj->is_pkp;
        $this->is_include_ppn = $this->obj->is_include_ppn;
        $this->ppn_percent = $this->obj->ppn_percent;
        $this->ppn_selisih = $this->obj->ppn_selisih;

        $this->diskon_type = $this->obj->diskon_type;
        $this->diskon = $this->obj->diskon;

        $details = $this->obj->details()->with(['produk', 'satuan', 'pesananPenjualan'])->get();
        $this->items = [];

        foreach ($details as $detail) {
            $suratJalanDetail = SuratJalanDetail::find($detail->surat_jalan_detail_id);
            $this->items[] = [
                'id' => $detail->id,
                'surat_jalan_id' => $suratJalanDetail?->header?->id,
                'pesanan_penjualan_id' => $detail->pesanan_penjualan_id,
                'pesanan_penjualan_kode' => $detail->pesananPenjualan?->kode,
                'pesanan_penjualan_detail_id' => $detail->pesanan_penjualan_detail_id,
                'surat_jalan_detail_id' => $detail->surat_jalan_detail_id,
                'produk_id' => $detail->produk_id,
                'produk_kode' => $detail->produk->kode,
                'produk_nama' => $detail->produk->nama,
                'satuan_id' => $detail->satuan_id,
                'satuan_nama' => $detail->satuan->nama,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan,
                'diskon_satuan' => $detail->diskon_satuan,
                'diskon_satuan_type' => $detail->diskon_satuan_type,
            ];
        }

        $details = $this->obj->fakturPenjualanServices()->with(['perintahService.customer'])->get();
        $this->items_service = [];

        foreach ($details as $detail) {
            $this->items_service[] = [
                'id' => $detail->id,
                'perintah_service_id' => $detail->perintahService->id,

                'perintah_service_kode' => $detail->perintahService->kode,
                'customer_nama' => $detail->perintahService->customer?->nama,
                'tanggal' => $detail->perintahService->tanggal,
                'grandtotal' => $detail->perintahService->totalBiayaService,
            ];
        }

        $details = $this->obj->fakturPenjualanSets()->with(['details'])->get();
        $this->items_set = [];

        foreach ($details as $detail) {
            $items = [];
            foreach ($detail->details()->with(['produk', 'satuan'])->get() as $value) {
                $items[] = [
                    'id' => $value->id,
                    'produk_id' => $value->produk_id,
                    'satuan_id' => $value->satuan_id,
                    'jumlah' => $value->jumlah,
                    'harga_satuan' => $value->harga_satuan,
                    'diskon_satuan_type' => $value->diskon_satuan_type,
                    'diskon_satuan' => $value->diskon_satuan,

                    'produk_kode' => $value->produk->kode,
                    'produk_nama' => $value->produk->nama,
                    'satuan_nama' => $value->satuan->nama,
                    'subtotal' => $value->subtotal,
                ];
            }
            $this->items_set[] = [
                'id' => $detail->id,
                'gudang_id' => $detail->gudang_id,
                'produk_teks' => $detail->produk_teks,
                'satuan_teks' => $detail->satuan_teks,
                'jumlah' => $detail->jumlah,
                'diskon_type' => $detail->diskon_type,
                'diskon' => $detail->diskon,
                'biaya_lain' => $detail->biaya_lain,
                'grandtotal' => $detail->total,
                'items' => $items,
            ];
        }

        $details = $this->obj->fakturPenjualanBebans()->with(['beban'])->get();
        $this->items_beban = [];

        foreach ($details as $detail) {
            $this->items_beban[] = [
                'id' => $detail->id,
                'beban_id' => $detail->beban_id,
                'beban_nama' => $detail->beban->nama,
                'jumlah' => $detail->jumlah,
            ];
        }
    }

    #[Computed(persist: true)]
    public function optionsInputServicePerintahServiceId()
    {
        return SH_PerintahService::selesai(
            $this->customer_id,
            $this->obj->fakturPenjualanServices
                ->pluck('perintah_service_id')
                ->toArray(),
        );
    }

    #[Computed(persist: true)]
    public function optionsJenisTransaksi()
    {
        return SH_JenisTransaksi::fakturPenjualan();
    }

    #[Computed(persist: true)]
    public function optionsCustomerId()
    {
        return SH_Customer::active();
    }

    #[Computed(persist: true)]
    public function optionsGudangId()
    {
        return SH_Gudang::user();
    }

    #[Computed(persist: true)]
    public function optionsInputSuratJalanId()
    {
        return SH_SuratJalan::belumSelesai(
            $this->customer_id,
            collect($this->items)->pluck('surat_jalan_id')->toArray(),
        );
    }

    #[Computed(persist: true)]
    public function optionsInputBebanBebanId()
    {
        return SH_Beban::active(false);
    }

    public function refreshDataProduk($params)
    {
        $new_id = $params['new_id'];

        $options = SH_SuratJalanDetail::fakturPenjualanViaSj($this->input_surat_jalan_id, collect($this->items)->pluck('surat_jalan_detail_id')->toArray());
        $this->dispatch('refresh_dropdown_input_produk_id', [
            'options' => $options,
            'value' => null,
        ]);

        $this->input_produk_id = $new_id;
        $this->dispatch('set_value_dropdown_input_produk_id', $this->input_produk_id);
        $this->updatedInputProdukId();
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

    public function selisihPpnUpdated($params)
    {
        $this->ppn_selisih = $params['ppn_selisih'];
    }

    public function openModalSelisihPpn()
    {
        $params = [
            'total_ppn' => $this->total_ppn - $this->ppn_selisih,
            'ppn_selisih' => $this->ppn_selisih,
        ];

        $this->dispatch('refreshInfo', $params)->to(ModalSelisihPpn::class);
    }

    public function updatedCustomerId()
    {
        $this->reset('items');

        $customer = Customer::find($this->customer_id);
        $this->tanggal_jatuh_tempo = _datetime_carbon_db($this->tanggal)->addDays($customer?->jatuh_tempo ?? 0)->format(Const_Date::DATETIME_FORMAT_OUTPUT);
        $this->telp = optional($customer)->telp;
        $this->handphone = optional($customer)->handphone;
        $this->alamat = optional($customer)->alamat;
        $this->kota = optional($customer)->kota;
        $this->kode_pos = optional($customer)->kode_pos;
        $this->provinsi = optional($customer)->provinsi;

        $options = SH_SuratJalan::belumSelesai($this->customer_id);
        $this->dispatch('refresh_dropdown_input_surat_jalan_id', [
            'options' => $options,
            'value' => null,
        ]);

        $options = SH_PerintahService::selesai($this->customer_id);
        $this->dispatch('refresh_dropdown_input_service_perintah_service_id', [
            'options' => $options,
            'value' => null,
        ]);
    }

    public function updatedInputSuratJalanId()
    {
        $this->reset('input_produk_id', 'input_satuan_id', 'input_satuan_nama', 'input_jumlah', 'input_harga_satuan', 'input_diskon_satuan_type', 'input_diskon_satuan');

        if ($this->input_surat_jalan_id) {
            $options = SH_SuratJalanDetail::fakturPenjualanViaSj($this->input_surat_jalan_id, collect($this->items)->pluck('surat_jalan_detail_id')->toArray());
            $this->dispatch('refresh_dropdown_input_produk_id', [
                'options' => $options,
                'value' => null,
            ]);
        }
    }

    public function updatedInputProdukId()
    {
        $suratJalanDetail = SuratJalanDetail::findOrFail($this->input_produk_id);
        $pesananPenjualanDetail = $suratJalanDetail->pesananPenjualanDetail;

        $this->input_jumlah = $suratJalanDetail->sisa_faktur;
        $this->input_harga_satuan = $pesananPenjualanDetail->harga_satuan;
        $this->input_diskon_satuan_type = $pesananPenjualanDetail->diskon_satuan_type;
        $this->input_diskon_satuan = $pesananPenjualanDetail->diskon_satuan;

        $this->input_satuan_id = $suratJalanDetail->satuan_id;
        $this->input_satuan_nama = $suratJalanDetail->satuan->nama;
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

        $total_produk = collect($this->items)->sum(function ($item) {
            return $item['subtotal'];
        });
        $total_produk = _round($total_produk);

        $total_set = collect($this->items_set)->sum(function ($item) {
            return $item['grandtotal'];
        });
        $total_set = _round($total_set);

        $total_service = collect($this->items_service)->sum(function ($item) {
            return $item['grandtotal'];
        });
        $total_service = _round($total_service);

        $total = $total_produk + $total_set + $total_service;

        // hitung harga net satuan per item, dengan tambahan diskon dan biaya footer
        $diskon = $this->diskon ?: 0;
        $diskon_type = $this->diskon_type;
        $this->total_beban = collect($this->items_beban)->sum('jumlah');

        $diskon_rupiah = 0;
        if ($diskon > 0) {
            if ($diskon_type == Const_Umum::DISKON_TYPE_VALUE) {
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
        $ppn += $this->ppn_selisih;

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

        $this->total = $total_produk;
        $this->total_set = $total_set;
        $this->total_service = $total_service;
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
            'input_surat_jalan_id' => ['required'],
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'gt:0'],
            'input_harga_satuan' => ['required', 'numeric', 'min:0'],
            'input_diskon_satuan_type' => [],
            'input_diskon_satuan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $suratJalanDetail = SuratJalanDetail::findOrFail($this->input_produk_id);
        $pesananPenjualanDetail = $suratJalanDetail->pesananPenjualanDetail;
        $pesananPenjualan = $pesananPenjualanDetail->header;
        $produk = Produk::find($pesananPenjualanDetail->produk_id);
        if ($this->diskon == 0 && $this->total_beban == 0) {
            $this->diskon_type = $pesananPenjualan->diskon_type;
            $this->diskon = $pesananPenjualan->diskon;
            foreach ($pesananPenjualan->pesananPenjualanBebans()->with('beban')->get() as $pesananPenjualanBeban) {
                $this->items_beban[] = [
                    'beban_id' => $pesananPenjualanBeban->beban_id,
                    'beban_nama' => $pesananPenjualanBeban->beban->nama,
                    'jumlah' => $pesananPenjualanBeban->jumlah,
                ];
            }
        }
        $satuan = Satuan::find($this->input_satuan_id);
        $diskon_satuan_type = $this->input_diskon_satuan ? $this->input_diskon_satuan_type : null;
        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;

        $this->items[] = [
            'id' => null,
            'surat_jalan_id' => $this->input_surat_jalan_id,
            'pesanan_penjualan_id' => $pesananPenjualan->id,
            'pesanan_penjualan_kode' => $pesananPenjualan->kode,
            'pesanan_penjualan_detail_id' => $pesananPenjualanDetail->id,
            'surat_jalan_detail_id' => $suratJalanDetail->id,
            'produk_id' => $produk->id,
            'produk_kode' => $produk->kode,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,
        ];

        $this->reset('input_surat_jalan_id', 'input_produk_id', 'input_satuan_id', 'input_satuan_nama', 'input_jumlah', 'input_harga_satuan', 'input_diskon_satuan', 'index_edit_item');
        $this->updatedInputSuratJalanId();
    }

    public function editItem()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_surat_jalan_id' => ['required'],
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'gt:0'],
            'input_harga_satuan' => ['required', 'numeric', 'min:0'],
            'input_diskon_satuan_type' => [],
            'input_diskon_satuan' => ['nullable', 'numeric', 'min:0'],
        ]);

        $suratJalanDetail = SuratJalanDetail::findOrFail($this->input_produk_id);
        $pesananPenjualanDetail = $suratJalanDetail->pesananPenjualanDetail;
        $pesananPenjualan = $pesananPenjualanDetail->header;
        $produk = Produk::find($pesananPenjualanDetail->produk_id);
        if ($this->diskon == 0 && $this->total_beban == 0) {
            $this->diskon_type = $pesananPenjualan->diskon_type;
            $this->diskon = $pesananPenjualan->diskon;
            foreach ($pesananPenjualan->pesananPenjualanBebans()->with('beban')->get() as $pesananPenjualanBeban) {
                $this->items_beban[] = [
                    'beban_id' => $pesananPenjualanBeban->beban_id,
                    'beban_nama' => $pesananPenjualanBeban->beban->nama,
                    'jumlah' => $pesananPenjualanBeban->jumlah,
                ];
            }
        }
        $satuan = Satuan::find($this->input_satuan_id);
        $diskon_satuan_type = $this->input_diskon_satuan ? $this->input_diskon_satuan_type : null;
        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'surat_jalan_id' => $this->input_surat_jalan_id,
            'pesanan_penjualan_id' => $pesananPenjualan->id,
            'pesanan_penjualan_kode' => $pesananPenjualan->kode,
            'pesanan_penjualan_detail_id' => $pesananPenjualanDetail->id,
            'surat_jalan_detail_id' => $suratJalanDetail->id,
            'produk_id' => $produk->id,
            'produk_kode' => $produk->kode,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,
        ];

        $this->reset('input_surat_jalan_id', 'input_produk_id', 'input_satuan_id', 'input_satuan_nama', 'input_jumlah', 'input_harga_satuan', 'input_diskon_satuan', 'index_edit_item');
        $this->updatedInputSuratJalanId();
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_surat_jalan_id = $item['surat_jalan_id'];
        $this->updatedInputSuratJalanId();
        $this->input_produk_id = $item['surat_jalan_detail_id'];
        $this->updatedInputProdukId();

        $this->input_satuan_id = $item['satuan_id'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_harga_satuan = $item['harga_satuan'];
        $this->input_diskon_satuan_type = $item['diskon_satuan_type'] ?: Const_Umum::DISKON_TYPE_VALUE;
        $this->input_diskon_satuan = $item['diskon_satuan'];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function addItemService()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_service_perintah_service_id' => ['required'],
        ]);

        $perintahService = PerintahService::with(['customer'])->find($this->input_service_perintah_service_id);

        $this->items_service[] = [
            'id' => null,
            'perintah_service_id' => $perintahService->id,

            'perintah_service_kode' => $perintahService->kode,
            'customer_nama' => $perintahService->customer?->nama,
            'tanggal' => $perintahService->tanggal,
            'grandtotal' => $perintahService->totalBiayaService,
        ];

        $this->reset('input_service_perintah_service_id');
    }

    public function editItemService()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_service_perintah_service_id' => ['required'],
        ]);

        $perintahService = PerintahService::with(['customer'])->find($this->input_service_perintah_service_id);

        $this->items_service[$this->index_edit_item_service] = [
            'id' => $this->items_service[$this->index_edit_item_service]['id'],
            'perintah_service_id' => $perintahService->id,

            'perintah_service_kode' => $perintahService->kode,
            'customer_nama' => $perintahService->customer?->nama,
            'tanggal' => $perintahService->tanggal,
            'grandtotal' => $perintahService->totalBiayaService,
        ];

        $this->reset('input_service_perintah_service_id', 'index_edit_item_service');
    }

    public function editService($index)
    {
        $this->index_edit_item_service = $index;

        $item = $this->items_service[$index];
        $this->input_service_perintah_service_id = $item['perintah_service_id'];
    }

    public function removeItemService($index)
    {
        unset($this->items_service[$index]);
        $this->items_service = array_values($this->items_service);
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
            'id' => null,
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
            'id' => $this->items_beban[$this->index_edit_item_beban]['id'],
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

    public function openModalSetEdit($index = null)
    {
        $items = [];
        if ($index !== null) {
            $items = $this->items_set[$index];
        }
        $params = [
            'index' => $index,
            'is_pkp' => $this->is_pkp,
            'is_include_ppn' => $this->is_include_ppn,
            'ppn_percent' => $this->ppn_percent,
            'items' => $items,
        ];

        $this->dispatch('refreshInfo', $params)->to(ModalSetEdit::class);
    }

    public function setUpdated($parameters)
    {
        $index = $parameters['index'];
        if ($index !== null) {
            $this->items_set[$index] = $parameters['items'];
        } else {
            $this->items_set[] = $parameters['items'];
        }
    }

    public function removeItemSet($index)
    {
        unset($this->items_set[$index]);
        $this->items_set = array_values($this->items_set);
    }

    public function confirmation($validated): bool
    {
        if ($validated['customer_id']) {
            $customer = Customer::find($validated['customer_id']);
            $totalPiutang = TransactionFunction::getPiutangCustomer($customer->id) + $this->grandtotal - $this->obj->mutasiTransaksi->jumlah;
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
        FakturPenjualanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        $this->calculateFooter();

        return view('admin.penjualan.faktur-penjualan-via-sj.edit')
            ->layout($this->layout);
    }
}
