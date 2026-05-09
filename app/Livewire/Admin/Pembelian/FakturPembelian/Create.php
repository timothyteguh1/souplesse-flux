<?php

namespace App\Livewire\Admin\Pembelian\FakturPembelian;

use App\Models\Setting;
use Livewire\Component;
use App\Models\Master\Beban;
use App\Models\Master\Gudang;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Supplier;
use Livewire\Attributes\Computed;
use App\Models\Master\ProdukSatuan;
use App\Traits\Livewire\WithCreateForm;
use App\Utilities\Constants\Const_Date;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\SelectHelpers\SH_Umum;
use App\Models\Pembelian\FakturPembelian;
use App\Utilities\Constants\Const_Setting;
use App\Utilities\SelectHelpers\Master\SH_Beban;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Services\Pembelian\FakturPembelianService;
use App\Utilities\SelectHelpers\Master\SH_Supplier;

class Create extends Component
{
    use WithCreateForm;

    public $model = FakturPembelian::class;
    public $menuTitle = 'Faktur Pembelian';
    public $cabang_id;
    public $kode;
    public $kode_faktur_supplier;
    public $jenis_transaksi = Const_Umum::JENIS_TRANSAKSI_FAKTUR_PEMBELIAN_KREDIT;
    public $tanggal;
    public $tanggal_jatuh_tempo;
    public $supplier_id;
    public $gudang_id;
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
    public $input_expired_date;
    public $input_jumlah;
    public $input_harga_satuan;
    public $input_diskon_satuan_type_1 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_1;
    public $input_diskon_satuan_type_2 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_2;
    public $input_diskon_satuan_type_3 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_3;
    public $input_diskon_satuan_type_4 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_4;
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

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'kode_faktur_supplier' => ['required'],
            'jenis_transaksi' => ['required'],
            'tanggal' => ['required'],
            'tanggal_jatuh_tempo' => ['required_if:jenis_transaksi,Kredit'],
            'supplier_id' => ['required'],
            'gudang_id' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'diskon_type' => ['required'],
            'diskon' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.expired_date' => [],
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
        $this->ppn_percent = Setting::fetch(Const_Setting::PPN_PERCENT) ?? 0;
        $gudangUtama = Gudang::where('nama', Const_Umum::GUDANG_UTAMA)->first();
        $this->gudang_id = $gudangUtama->id;
    }

    #[Computed(persist: true)]
    public function optionsSupplierId()
    {
        return SH_Supplier::active();
    }

    #[Computed(persist: true)]
    public function optionsGudangId()
    {
        return SH_Gudang::user();
    }

    #[Computed(persist: true)]
    public function optionsJenisTransaksi()
    {
        return SH_Umum::jenisTransaksiFakturPembelian();
    }

    #[Computed(persist: true)]
    public function optionsInputBebanBebanId()
    {
        return SH_Beban::active();
    }

    public function openModalBayar()
    {
        $validated = $this->validate();
        $params = [
            'transaksi' => $validated,
            'grandtotal' => $this->grandtotal,
        ];

        $this->dispatch('refreshInfo', $params)->to(ModalPembayaran::class);
        $this->skipRender();
    }

    public function updatedSupplierId()
    {
        $supplier = Supplier::find($this->supplier_id);
        $this->alamat = optional($supplier)->alamat;
        $this->kota = optional($supplier)->kota;
        $this->kode_pos = optional($supplier)->kode_pos;
        $this->provinsi = optional($supplier)->provinsi;
        $this->is_pkp = optional($supplier)->is_pkp;
        $this->is_include_ppn = optional($supplier)->is_include_ppn;

        $jumlah_hari = $supplier?->jatuh_tempo ?? 0;
        $tanggal_jatuh_tempo = _datetime_carbon_db($this->tanggal)
            ->addDays($jumlah_hari)
            ->format(Const_Date::DATETIME_FORMAT_OUTPUT);

        $this->tanggal_jatuh_tempo = $tanggal_jatuh_tempo;

        $options = SH_Produk::activeSupplier($this->supplier_id);
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

        $this->input_satuan_id = $produk->default_satuan_beli_id;
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

        $this->input_harga_satuan = $produkSatuan?->harga_beli ?? 0;
    }

    public function calculateFooter()
    {
        // hitung harga net satuan per item, diskon dan beban footer tidak di hitung
        foreach ($this->items as $index => $item) {
            $jumlah = $item['jumlah'];
            $harga_satuan = $item['harga_satuan'];
            $diskon_satuan_1 = $item['diskon_satuan_1'];
            $diskon_satuan_type_1 = $item['diskon_satuan_type_1'];
            $diskon_satuan_2 = $item['diskon_satuan_2'];
            $diskon_satuan_type_2 = $item['diskon_satuan_type_2'];
            $diskon_satuan_3 = $item['diskon_satuan_3'];
            $diskon_satuan_type_3 = $item['diskon_satuan_type_3'];
            $diskon_satuan_4 = $item['diskon_satuan_4'];
            $diskon_satuan_type_4 = $item['diskon_satuan_type_4'];

            $diskon_satuan_persen_1 = 0;
            $diskon_satuan_rupiah_1 = 0;
            if ($diskon_satuan_1 > 0) {
                if ($diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_RP) {
                    $diskon_satuan_rupiah_1 = $diskon_satuan_1;
                    $diskon_satuan_persen_1 = $harga_satuan != 0 ? $diskon_satuan_rupiah_1 * 100 / $harga_satuan : 0;
                }
                if ($diskon_satuan_type_1 == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskon_satuan_persen_1 = $diskon_satuan_1;
                    $diskon_satuan_rupiah_1 = $harga_satuan * $diskon_satuan_persen_1 / 100;
                }
            }

            $harga_setelah_diskon_1 = $harga_satuan - $diskon_satuan_rupiah_1;
            $diskon_satuan_persen_2 = 0;
            $diskon_satuan_rupiah_2 = 0;

            if ($diskon_satuan_2 > 0) {
                if ($diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_RP) {
                    $diskon_satuan_rupiah_2 = $diskon_satuan_2;
                    $diskon_satuan_persen_2 = $harga_setelah_diskon_1 != 0 ? $diskon_satuan_rupiah_2 * 100 / $harga_setelah_diskon_1 : 0;
                }
                if ($diskon_satuan_type_2 == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskon_satuan_persen_2 = $diskon_satuan_2;
                    $diskon_satuan_rupiah_2 = $harga_setelah_diskon_1 * $diskon_satuan_persen_2 / 100;
                }
            }

            $harga_setelah_diskon_2 = $harga_setelah_diskon_1 - $diskon_satuan_rupiah_2;
            $diskon_satuan_persen_3 = 0;
            $diskon_satuan_rupiah_3 = 0;

            if ($diskon_satuan_3 > 0) {
                if ($diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_RP) {
                    $diskon_satuan_rupiah_3 = $diskon_satuan_3;
                    $diskon_satuan_persen_3 = $harga_setelah_diskon_2 != 0 ? $diskon_satuan_rupiah_3 * 100 / $harga_setelah_diskon_2 : 0;
                }
                if ($diskon_satuan_type_3 == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskon_satuan_persen_3 = $diskon_satuan_3;
                    $diskon_satuan_rupiah_3 = $harga_setelah_diskon_2 * $diskon_satuan_persen_3 / 100;
                }
            }

            $harga_setelah_diskon_3 = $harga_setelah_diskon_2 - $diskon_satuan_rupiah_3;
            $diskon_satuan_persen_4 = 0;
            $diskon_satuan_rupiah_4 = 0;

            if ($diskon_satuan_4 > 0) {
                if ($diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_RP) {
                    $diskon_satuan_rupiah_4 = $diskon_satuan_4;
                    $diskon_satuan_persen_4 = $harga_setelah_diskon_3 != 0 ? $diskon_satuan_rupiah_4 * 100 / $harga_setelah_diskon_3 : 0;
                }
                if ($diskon_satuan_type_4 == Const_Umum::DISKON_TYPE_PERCENT) {
                    $diskon_satuan_persen_4 = $diskon_satuan_4;
                    $diskon_satuan_rupiah_4 = $harga_setelah_diskon_3 * $diskon_satuan_persen_4 / 100;
                }
            }

            $harga_net_satuan = $harga_setelah_diskon_3 - $diskon_satuan_rupiah_4;
            $subtotal = $harga_net_satuan * $jumlah;

            $diskon_satuan_rupiah = ($harga_satuan - $harga_net_satuan);
            $diskon_satuan_persen = $harga_satuan == 0 ? 0 : ($diskon_satuan_rupiah * 100) / $harga_satuan;

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
        $this->total_beban = collect($this->items_beban)->sum('jumlah');

        $diskon_rupiah = 0;
        if ($diskon > 0) {
            if ($diskon_type == Const_Umum::DISKON_TYPE_RP) {
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

            $dpp_satuan = $total == 0 ? 0 : _round($dpp / $total * $subtotal / $jumlah);
            $ppn_satuan = $dpp == 0 ? 0 : _round($dpp_satuan / $dpp * $ppn);
            $diskon_satuan_footer = $dpp == 0 ? 0 : _round($dpp_satuan / $dpp * $diskon_rupiah);
            $harga_net_satuan_akhir = $harga_net_satuan - $diskon_satuan_footer;

            $this->items[$index]['diskon_satuan_footer'] = $diskon_satuan_footer;
            $this->items[$index]['harga_net_satuan_akhir'] = $harga_net_satuan_akhir;
            $this->items[$index]['ppn_satuan'] = $ppn_satuan;
            $this->items[$index]['dpp_satuan'] = $dpp_satuan;
        }

        // update footer
        $this->total = $total;
        $this->total_dpp = $dpp;
        $this->total_ppn = $ppn;
        $this->grandtotal = $dpp + $ppn + $this->total_beban;
    }

    public function addItem()
    {
        if (!$this->supplier_id) {
            $this->addError('flash_danger', 'Supplier harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        if (!$this->gudang_id) {
            $this->addError('flash_danger', 'Gudang harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_expired_date' => [],
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
        $expired_date = $this->input_expired_date;
        $diskon_satuan_type_1 = $this->input_diskon_satuan_1 ? $this->input_diskon_satuan_type_1 : null;
        $diskon_satuan_1 = $this->input_diskon_satuan_1 ?: 0;
        $diskon_satuan_type_2 = $this->input_diskon_satuan_2 ? $this->input_diskon_satuan_type_2 : null;
        $diskon_satuan_2 = $this->input_diskon_satuan_2 ?: 0;
        $diskon_satuan_type_3 = $this->input_diskon_satuan_3 ? $this->input_diskon_satuan_type_3 : null;
        $diskon_satuan_3 = $this->input_diskon_satuan_3 ?: 0;
        $diskon_satuan_type_4 = $this->input_diskon_satuan_4 ? $this->input_diskon_satuan_type_4 : null;
        $diskon_satuan_4 = $this->input_diskon_satuan_4 ?: 0;

        $this->items[] = [
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'expired_date' => $expired_date,
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
            'input_expired_date',
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
        if (!$this->supplier_id) {
            $this->addError('flash_danger', 'Supplier harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        if (!$this->gudang_id) {
            $this->addError('flash_danger', 'Gudang harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_expired_date' => [],
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
        $expired_date = $this->input_expired_date;
        $diskon_satuan_type_1 = $this->input_diskon_satuan_1 ? $this->input_diskon_satuan_type_1 : null;
        $diskon_satuan_1 = $this->input_diskon_satuan_1 ?: 0;
        $diskon_satuan_type_2 = $this->input_diskon_satuan_2 ? $this->input_diskon_satuan_type_2 : null;
        $diskon_satuan_2 = $this->input_diskon_satuan_2 ?: 0;
        $diskon_satuan_type_3 = $this->input_diskon_satuan_3 ? $this->input_diskon_satuan_type_3 : null;
        $diskon_satuan_3 = $this->input_diskon_satuan_3 ?: 0;
        $diskon_satuan_type_4 = $this->input_diskon_satuan_4 ? $this->input_diskon_satuan_type_4 : null;
        $diskon_satuan_4 = $this->input_diskon_satuan_4 ?: 0;

        $this->items[$this->index_edit_item] = [
            'produk_id' => $produk->id,
            'produk_nama' => $produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'expired_date' => $expired_date,
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
            'input_expired_date',
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
        $this->input_expired_date = $item['expired_date'];
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
        if (!$this->supplier_id) {
            $this->addError('flash_danger', 'Supplier harus dipilih terlebih dahulu.');
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
        if (!$this->supplier_id) {
            $this->addError('flash_danger', 'Supplier harus dipilih terlebih dahulu.');
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

    public function submit($validated)
    {
        return FakturPembelianService::create($validated);
    }

    public function render()
    {
        $this->calculateFooter();

        return view('admin.pembelian.faktur-pembelian.create')->layout($this->layout);
    }
}
