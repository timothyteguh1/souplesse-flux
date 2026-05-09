<?php

namespace App\Livewire\Admin\Penjualan\PesananPenjualan;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Master\Promo;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Customer;
use Livewire\Attributes\Computed;
use App\Models\Master\ProdukSatuan;
use App\Traits\Livewire\WithEditForm;
use App\Utilities\Constants\Const_Umum;
use App\Models\Penjualan\PesananPenjualan;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Services\Penjualan\PesananPenjualanService;

class Edit extends Component
{
    use WithEditForm;

    public $model = PesananPenjualan::class;
    public $menuTitle = 'Pesanan Penjualan';
    public PesananPenjualan $obj;
    public $kode;
    public $tanggal;
    public $customer_id;
    public $keterangan;
    public $alamat;
    public $kota;
    public $kode_pos;
    public $provinsi;
    public $kelas_customer;
    public bool $is_pkp = false;
    public bool $is_include_ppn = false;
    public $ppn_percent;
    public $items = [];
    public $input_produk_id;
    public $input_satuan_id;
    public $input_jumlah;
    public $input_harga_satuan;
    public $input_subtotal;
    public $input_diskon_satuan_type_1 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_1;
    public $input_diskon_satuan_type_2 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_2;
    public $input_diskon_satuan_type_3 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_3;
    public $input_diskon_satuan_type_4 = Const_Umum::DISKON_TYPE_PERCENT;
    public $input_diskon_satuan_4;
    public $index_edit_item = null;
    public $total = 0;
    public $total_dpp = 0;
    public $total_ppn = 0;
    public $diskon_type = Const_Umum::DISKON_TYPE_PERCENT;
    public $diskon = 0;
    public $grandtotal = 0;
    protected $listeners = [
        'pilihPromoUpdated' => 'pilihPromoUpdated',
    ];

    protected function rules(): array
    {
        return [
            'tanggal' => ['required'],
            'customer_id' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'diskon_type' => ['required'],
            'diskon' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.id' => [],
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
            'items.*.is_promo_grosir_applied' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->tanggal = $this->obj->tanggal;
        $this->customer_id = $this->obj->customer_id;
        $this->is_pkp = $this->obj->is_pkp;
        $this->is_include_ppn = $this->obj->is_include_ppn;
        $this->ppn_percent = $this->obj->ppn_percent;
        $this->keterangan = $this->obj->keterangan;
        $this->alamat = $this->obj->customer->alamat;
        $this->kota = $this->obj->customer->kota;
        $this->kode_pos = $this->obj->customer->kode_pos;
        $this->provinsi = $this->obj->customer->provinsi;
        $this->kelas_customer = $this->obj->customer?->kelasCustomer?->nama;

        $this->diskon_type = $this->obj->diskon_type;
        $this->diskon = $this->obj->diskon;

        $details = $this->obj->details()->with(['produk', 'satuan'])->get();
        $this->items = [];

        foreach ($details as $detail) {
            $this->items[] = [
                'id' => $detail->id,
                'produk_id' => $detail->produk_id,
                'produk_nama' => $detail->produk->nama,
                'satuan_id' => optional($detail->satuan)->id,
                'satuan_nama' => optional($detail->satuan)->nama,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan,
                'diskon_satuan_1' => $detail->diskon_satuan_1,
                'diskon_satuan_type_1' => $detail->diskon_satuan_type_1,
                'diskon_satuan_2' => $detail->diskon_satuan_2,
                'diskon_satuan_type_2' => $detail->diskon_satuan_type_2,
                'diskon_satuan_3' => $detail->diskon_satuan_3,
                'diskon_satuan_type_3' => $detail->diskon_satuan_type_3,
                'diskon_satuan_4' => $detail->diskon_satuan_4,
                'diskon_satuan_type_4' => $detail->diskon_satuan_type_4,
                'is_promo_grosir_applied' => $detail->is_promo_grosir_applied,
            ];
        }
    }

    #[Computed(persist: true)]
    public function optionsInputProdukId()
    {
        return SH_Produk::stokCabangWithStok(false);
    }

    public function openModalListPromo()
    {
        $params = [];
        $this->dispatch('refreshInfo', $params)->to(ModalListPromo::class);
        $this->skipRender();
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
            $this->kelas_customer = optional($customer)->kelasCustomer?->nama;
            $this->is_pkp = optional($customer)->is_pkp ? true : false;
            $this->is_include_ppn = optional($customer)->is_include_ppn ? true : false;
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

    public function updatedInputJumlah()
    {
        $this->input_subtotal = (float) $this->input_jumlah * (float) $this->input_harga_satuan;
    }

    public function updatedInputHargaSatuan()
    {
        $this->input_subtotal = (float) $this->input_jumlah * (float) $this->input_harga_satuan;
    }

    public function cekPromoGrosir()
    {
        /**
         * 1) Kumpulkan produk sekaligus untuk menghindari N+1
         * 2) Bangun arrSuppliers yang berisi jumlah, subtotal, dan list indeks item
         */

        // ambil semua product ids dari items
        $productIds = array_filter(array_map(fn($i) => $i['produk_id'] ?? null, $this->items));
        $products = Produk::whereIn('id', $productIds)->get()->keyBy('id');

        $arrSuppliers = [];
        $itemSupplierMap = []; // map index => supplierId

        foreach ($this->items as $index => $item) {
            $produk = $products->get($item['produk_id']); // lebih efisien
            $supplierId = $produk ? $produk->supplier_id : null;

            $itemSupplierMap[$index] = $supplierId;

            if (! isset($arrSuppliers[$supplierId])) {
                $arrSuppliers[$supplierId] = [
                    'jumlah' => 0,
                    'subtotal' => 0,
                    'items' => [], // simpan indeks item terkait
                ];
            }

            $arrSuppliers[$supplierId]['jumlah'] += $item['jumlah'];
            $arrSuppliers[$supplierId]['subtotal'] += ($item['harga_satuan'] * $item['jumlah']);
            $arrSuppliers[$supplierId]['items'][] = $index;
        }

        /**
         * Ambil semua promo yg aktif
         */
        $promos = Promo::where('is_promo_grosir', true)
            ->where('status', Const_Status::AKTIF)
            ->whereDate('tanggal_awal', '<=', Carbon::now())
            ->whereDate('tanggal_akhir', '>=', Carbon::now())
            ->with('promoSuppliers')
            ->get();

        /**
         * Untuk setiap supplier: cari bestPromo yang memenuhi syarat hanya untuk supplier tersebut
         */
        $bestPromoBySupplier = []; // supplierId => promo|null

        foreach ($arrSuppliers as $supplierId => $data) {
            $eligibleForThisSupplier = $promos->filter(function ($promo) use ($supplierId, $data) {
                // berlaku untuk semua supplier jika promoSuppliers kosong
                $appliesToSupplier = $promo->promoSuppliers->isEmpty() || $promo->promoSuppliers->contains('supplier_id', $supplierId);

                if (! $appliesToSupplier) {
                    return false;
                }

                $subtotal = $data['subtotal'];
                $jumlah = $data['jumlah'];

                $minRpOk = ($promo->min_pembelian_rp == 0) || ($subtotal >= $promo->min_pembelian_rp);
                $minJumlahOk = ($promo->min_pembelian_jumlah == 0) || ($jumlah >= $promo->min_pembelian_jumlah);

                // Promo berlaku jika salah satu syarat terpenuhi (sesuai logika awalmu).
                return $minRpOk || $minJumlahOk;
            });

            $best = $eligibleForThisSupplier->sortByDesc('diskon_satuan_1')->first();
            $bestPromoBySupplier[$supplierId] = $best ?: null;
        }

        /**
         * Terapkan promo per supplier — hanya ke item yang suppliernya sesuai
         */
        foreach ($arrSuppliers as $supplierId => $data) {
            $bestPromo = $bestPromoBySupplier[$supplierId];

            foreach ($data['items'] as $index) {
                // jika produk hilang / supplier null -> skip atau rollback sesuai kebutuhan
                if (! isset($this->items[$index])) {
                    continue;
                }

                // jika tidak ada best promo untuk supplier ini -> hapus flag (shift diskon sesuai logikamu)
                if (! $bestPromo) {
                    if ($this->items[$index]['is_promo_grosir_applied']) {
                        $this->items[$index]['diskon_satuan_1'] = $this->items[$index]['diskon_satuan_2'];
                        $this->items[$index]['diskon_satuan_type_1'] = $this->items[$index]['diskon_satuan_type_2'];

                        $this->items[$index]['diskon_satuan_2'] = $this->items[$index]['diskon_satuan_3'];
                        $this->items[$index]['diskon_satuan_type_2'] = $this->items[$index]['diskon_satuan_type_3'];

                        $this->items[$index]['diskon_satuan_3'] = $this->items[$index]['diskon_satuan_4'];
                        $this->items[$index]['diskon_satuan_type_3'] = $this->items[$index]['diskon_satuan_type_4'];

                        // bersihkan slot ke-4 juga agar konsisten
                        $this->items[$index]['diskon_satuan_4'] = 0;
                        $this->items[$index]['diskon_satuan_type_4'] = null;

                        $this->items[$index]['is_promo_grosir_applied'] = false;
                    }
                    continue;
                }

                // Ada best promo untuk supplier ini -> apply hanya pada item ini
                if (! $this->items[$index]['is_promo_grosir_applied']) {
                    // shift diskon existing ke slot 2..4 dan pasang bestPromo jadi slot1
                    $diskon_satuan_1 = $this->items[$index]['diskon_satuan_1'];
                    $diskon_satuan_type_1 = $this->items[$index]['diskon_satuan_type_1'];
                    $diskon_satuan_2 = $this->items[$index]['diskon_satuan_2'];
                    $diskon_satuan_type_2 = $this->items[$index]['diskon_satuan_type_2'];
                    $diskon_satuan_3 = $this->items[$index]['diskon_satuan_3'];
                    $diskon_satuan_type_3 = $this->items[$index]['diskon_satuan_type_3'];

                    $this->items[$index]['diskon_satuan_1'] = $bestPromo->diskon_satuan_1;
                    $this->items[$index]['diskon_satuan_type_1'] = $bestPromo->diskon_satuan_type_1;

                    $this->items[$index]['diskon_satuan_2'] = $diskon_satuan_1;
                    $this->items[$index]['diskon_satuan_type_2'] = $diskon_satuan_type_1;

                    $this->items[$index]['diskon_satuan_3'] = $diskon_satuan_2;
                    $this->items[$index]['diskon_satuan_type_3'] = $diskon_satuan_type_2;

                    $this->items[$index]['diskon_satuan_4'] = $diskon_satuan_3;
                    $this->items[$index]['diskon_satuan_type_4'] = $diskon_satuan_type_3;
                    $this->items[$index]['is_promo_grosir_applied'] = true;
                } else {
                    // Sudah applied — cek apakah bestPromo berubah (update slot1 saja jika berubah)
                    if (
                        $this->items[$index]['diskon_satuan_1'] != $bestPromo->diskon_satuan_1 ||
                        $this->items[$index]['diskon_satuan_type_1'] != $bestPromo->diskon_satuan_type_1
                    ) {
                        $this->items[$index]['diskon_satuan_1'] = $bestPromo->diskon_satuan_1;
                        $this->items[$index]['diskon_satuan_type_1'] = $bestPromo->diskon_satuan_type_1;
                    }
                }
            }
        }
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
            $diskon_satuan_footer = $total == 0 ? 0 : ($subtotal * $diskon_rupiah / $total) / $jumlah;
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

        if (
            (($this->input_diskon_satuan_3 === null || $this->input_diskon_satuan_3 === '') && $this->input_diskon_satuan_4 !== null)
            || (($this->input_diskon_satuan_2 === null || $this->input_diskon_satuan_2 === '') && $this->input_diskon_satuan_3 !== null)
            || (($this->input_diskon_satuan_1 === null || $this->input_diskon_satuan_1 === '') && $this->input_diskon_satuan_2 !== null)
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
            'id' => null,
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
            'is_promo_grosir_applied' => false,
        ];

        $this->resetDetail();
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

        if (
            (($this->input_diskon_satuan_3 === null || $this->input_diskon_satuan_3 === '') && $this->input_diskon_satuan_4 !== null)
            || (($this->input_diskon_satuan_2 === null || $this->input_diskon_satuan_2 === '') && $this->input_diskon_satuan_3 !== null)
            || (($this->input_diskon_satuan_1 === null || $this->input_diskon_satuan_1 === '') && $this->input_diskon_satuan_2 !== null)
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
            'id' => $this->items[$this->index_edit_item]['id'],
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
            'is_promo_grosir_applied' => $this->items[$this->index_edit_item]['is_promo_grosir_applied'],
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
        $this->input_diskon_satuan_type_1 = $item['diskon_satuan_type_1'] ?: Const_Umum::DISKON_TYPE_PERCENT;
        $this->input_diskon_satuan_1 = $item['diskon_satuan_1'];
        $this->input_diskon_satuan_type_2 = $item['diskon_satuan_type_2'] ?: Const_Umum::DISKON_TYPE_PERCENT;
        $this->input_diskon_satuan_2 = $item['diskon_satuan_2'];
        $this->input_diskon_satuan_type_3 = $item['diskon_satuan_type_3'] ?: Const_Umum::DISKON_TYPE_PERCENT;
        $this->input_diskon_satuan_3 = $item['diskon_satuan_3'];
        $this->input_diskon_satuan_type_4 = $item['diskon_satuan_type_4'] ?: Const_Umum::DISKON_TYPE_PERCENT;
        $this->input_diskon_satuan_4 = $item['diskon_satuan_4'];

        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    private function resetDetail()
    {
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
            'input_subtotal',
            'index_edit_item',
        );
    }

    public function pilihPromoUpdated($parameters)
    {
        $promo = Promo::find($parameters['promo_id']);
        $customer = Customer::find($this->customer_id);

        if ($customer?->kelasCustomer?->nama == "GROSIR" && $this->input_diskon_satuan_1) {
            $this->input_diskon_satuan_2 = $promo?->diskon_satuan_1;
            $this->input_diskon_satuan_type_2 = $promo?->diskon_satuan_type_1;
            $this->input_diskon_satuan_3 = $promo?->diskon_satuan_2;
            $this->input_diskon_satuan_type_3 = $promo?->diskon_satuan_type_2;
            $this->input_diskon_satuan_4 = $promo?->diskon_satuan_3;
            $this->input_diskon_satuan_type_4 = $promo?->diskon_satuan_type_3;
        } else {
            $this->input_diskon_satuan_1 = $promo?->diskon_satuan_1;
            $this->input_diskon_satuan_type_1 = $promo?->diskon_satuan_type_1;
            $this->input_diskon_satuan_2 = $promo?->diskon_satuan_2;
            $this->input_diskon_satuan_type_2 = $promo?->diskon_satuan_type_2;
            $this->input_diskon_satuan_3 = $promo?->diskon_satuan_3;
            $this->input_diskon_satuan_type_3 = $promo?->diskon_satuan_type_3;
            $this->input_diskon_satuan_4 = $promo?->diskon_satuan_4;
            $this->input_diskon_satuan_type_4 = $promo?->diskon_satuan_type_4;
        }
    }

    public function submit($validated)
    {
        PesananPenjualanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        $this->calculateFooter();

        return view('admin.penjualan.pesanan-penjualan.edit')
            ->layout($this->layout);
    }
}
