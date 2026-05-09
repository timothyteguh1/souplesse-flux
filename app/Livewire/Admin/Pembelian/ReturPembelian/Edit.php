<?php

namespace App\Livewire\Admin\Pembelian\ReturPembelian;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Supplier;
use App\Models\Master\ProdukSatuan;
use App\Traits\Livewire\WithEditForm;
use App\Utilities\Constants\Const_Umum;
use App\Models\Pembelian\ReturPembelian;
use App\Models\Pembelian\FakturPembelian;
use App\Models\Pembelian\ReturPembelianDetail;
use App\Models\Pembelian\FakturPembelianDetail;
use App\Services\Pembelian\ReturPembelianService;
use App\Utilities\SelectHelpers\Master\SH_Produk;
use App\Utilities\SelectHelpers\Transaksi\Pembelian\SH_FakturPembelian;
use App\Utilities\SelectHelpers\Transaksi\Pembelian\SH_FakturPembelianDetail;

class Edit extends Component
{
    use WithEditForm;

    public $model = ReturPembelian::class;
    public $menuTitle = 'Retur Pembelian';
    public ReturPembelian $obj;
    public $kode;
    public $tanggal;
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
    public $input_faktur_pembelian_id;
    public $input_faktur_pembelian_detail_id;
    public $input_satuan_id;
    public $input_jumlah;
    public $input_tanggal_faktur;
    public $input_harga_satuan;
    public $input_diskon_satuan;
    public $index_edit_item = null;
    public $total = 0;
    public $total_dpp = 0;
    public $total_ppn = 0;
    public $grandtotal = 0;

    protected function rules(): array
    {
        return [
            'tanggal' => ['required'],
            'supplier_id' => ['required'],
            'gudang_id' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.id' => [],
            'items.*.faktur_pembelian_detail_id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->tanggal = $this->obj->tanggal;
        $this->supplier_id = $this->obj->supplier_id;
        $this->gudang_id = $this->obj->gudang_id;
        $this->keterangan = $this->obj->keterangan;
        $this->alamat = $this->obj->supplier->alamat;
        $this->kota = $this->obj->supplier->kota;
        $this->kode_pos = $this->obj->supplier->kode_pos;
        $this->provinsi = $this->obj->supplier->provinsi;
        $this->is_pkp = $this->obj->is_pkp;
        $this->is_include_ppn = $this->obj->is_include_ppn;
        $this->ppn_percent = $this->obj->ppn_percent;

        $details = $this->obj->details()->with(['produk', 'satuan', 'fakturPembelianDetail.header'])->get();
        $this->items = [];

        foreach ($details as $detail) {
            $this->items[] = [
                'id' => $detail->id,
                'faktur_pembelian_detail_id' => $detail->faktur_pembelian_detail_id,
                'produk_id' => $detail->produk_id,
                'satuan_id' => $detail->satuan_id,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan,
                'diskon_satuan' => $detail->diskon_satuan,
                'diskon_satuan_type' => $detail->diskon_satuan_type,

                'faktur_pembelian_id' => $detail->fakturPembelianDetail->header?->id,
                'faktur_pembelian_kode' => $detail->fakturPembelianDetail->header?->kode,
                'faktur_pembelian_supplier_kode' => $detail->fakturPembelianDetail->header?->kode_faktur_supplier,
                'tanggal_faktur' => $detail->fakturPembelianDetail->header->tanggal,
                'produk_nama' => $detail->produk->nama,
                'satuan_nama' => $detail->satuan->nama,
            ];
        }
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

        $this->items = [];

        $options = SH_FakturPembelian::all($this->supplier_id, is_show_sisa_utang: false);
        $this->dispatch('refresh_dropdown_input_faktur_pembelian_id', [
            'options' => $options,
            'value' => null,
        ]);
        $this->reset('input_faktur_pembelian_id', 'input_faktur_pembelian_detail_id', 'input_satuan_id', 'input_jumlah', 'input_tanggal_faktur', 'input_harga_satuan', 'input_diskon_satuan');
    }

    public function updatedInputFakturPembelianId()
    {
        $this->reset('input_faktur_pembelian_detail_id', 'input_satuan_id', 'input_jumlah', 'input_tanggal_faktur', 'input_harga_satuan', 'input_diskon_satuan');

        $fakturPembelian = FakturPembelian::find($this->input_faktur_pembelian_id);
        $this->input_tanggal_faktur = $fakturPembelian?->tanggal;

        $options = SH_FakturPembelianDetail::detailProduk($this->input_faktur_pembelian_id);
        $this->dispatch('refresh_dropdown_input_faktur_pembelian_detail_id', [
            'options' => $options,
            'value' => null,
        ]);
        $this->updatedInputFakturPembelianDetailId();
    }

    public function updatedInputFakturPembelianDetailId()
    {
        $fakturPembelianDetail = FakturPembelianDetail::find($this->input_faktur_pembelian_detail_id);
        $produk = Produk::find($fakturPembelianDetail?->produk_id);

        if (!$produk) {
            $this->dispatch('refresh_dropdown_input_satuan_id', [
                'options' => [],
                'value' => null,
            ]);

            $this->input_satuan_id = null;
            $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
            $this->updatedInputSatuanId();
            $this->reset('input_harga_satuan', 'input_diskon_satuan');
            return;
        }

        $options = SH_Produk::satuansStokGudang($produk->id, $this->gudang_id, showTersedia: false);
        $this->dispatch('refresh_dropdown_input_satuan_id', [
            'options' => $options,
            'value' => null,
        ]);

        $produkTerretur = ReturPembelianDetail::where('faktur_pembelian_detail_id', $this->input_faktur_pembelian_detail_id)->sum('jumlah');

        $this->input_satuan_id = $fakturPembelianDetail->satuan_id;
        $this->input_harga_satuan = $fakturPembelianDetail->harga_satuan;
        $this->input_diskon_satuan = $fakturPembelianDetail->diskon_satuan_rupiah + $fakturPembelianDetail->diskon_satuan_footer - $fakturPembelianDetail->beban_satuan_footer;
        $this->input_jumlah = $fakturPembelianDetail->jumlah - $produkTerretur;
        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
        $this->updatedInputSatuanId();
    }

    public function updatedInputSatuanId()
    {
        $fakturPembelianDetail = FakturPembelianDetail::find($this->input_faktur_pembelian_detail_id);
        if (!$fakturPembelianDetail) {
            return;
        }

        if ($fakturPembelianDetail->satuan_id == $this->input_satuan_id) {
            $this->input_harga_satuan = $fakturPembelianDetail->harga_satuan;
            return;
        }

        $produk = Produk::find($fakturPembelianDetail->produk_id);
        $satuan = Satuan::find($this->input_satuan_id);

        if (!$produk || !$satuan) {
            return;
        }

        $produkSatuan = ProdukSatuan::query()
            ->where('produk_id', $produk->id)
            ->where('satuan_id', $satuan->id)
            ->first();

        $produkSatuanLama = ProdukSatuan::query()
            ->where('produk_id', $produk->id)
            ->where('satuan_id', $fakturPembelianDetail->satuan_id)
            ->first();

        $this->input_harga_satuan = $this->input_harga_satuan * ($produkSatuan?->konversi / $produkSatuanLama?->konversi);
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

        foreach ($this->items as $index => $item) {
            $jumlah = $item['jumlah'];
            $harga_net_satuan = $item['harga_net_satuan'];
            $subtotal = $item['subtotal'];

            $ppn_satuan = 0;
            $dpp_satuan = 0;
            if ($this->is_pkp) {
                if ($this->is_include_ppn) {
                    $ppn_satuan = _ppn_value($harga_net_satuan, $this->ppn_percent, true);
                    $dpp_satuan = _round($harga_net_satuan - $ppn_satuan, 2);
                } else {
                    $ppn_satuan = _ppn_value($harga_net_satuan, $this->ppn_percent);
                    $dpp_satuan = _round($harga_net_satuan, 2);
                }
            }

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
        $this->grandtotal = $total  + $total_ppn;
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

        if (!$this->gudang_id) {
            $this->addError('flash_danger', 'Gudang harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_faktur_pembelian_id' => ['required'],
            'input_faktur_pembelian_detail_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
            'input_tanggal_faktur' => ['required'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan' => ['required'],
        ]);

        $fakturPembelianDetail = FakturPembelianDetail::find($this->input_faktur_pembelian_detail_id);
        $produk = Produk::find($fakturPembelianDetail->produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $diskon_satuan_type = Const_Umum::DISKON_TYPE_RP;
        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;

        $this->items[] = [
            'id' => null,
            'faktur_pembelian_detail_id' => $this->input_faktur_pembelian_detail_id,
            'produk_id' => $produk->id,
            'satuan_id' => $satuan->id,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,

            'faktur_pembelian_id' => $fakturPembelianDetail->header?->id,
            'faktur_pembelian_kode' => $fakturPembelianDetail->header?->kode,
            'tanggal_faktur' => $this->input_tanggal_faktur,
            'produk_nama' => $produk->nama,
            'satuan_nama' => $satuan->nama,
        ];

        $this->reset('input_faktur_pembelian_id', 'input_faktur_pembelian_detail_id', 'input_satuan_id', 'input_jumlah', 'input_tanggal_faktur', 'input_harga_satuan', 'input_diskon_satuan');
        $this->updatedInputFakturPembelianId();
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
            'input_faktur_pembelian_id' => ['required'],
            'input_faktur_pembelian_detail_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
            'input_tanggal_faktur' => ['required'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan' => ['required'],
        ]);

        $fakturPembelianDetail = FakturPembelianDetail::find($this->input_faktur_pembelian_detail_id);
        $produk = Produk::find($fakturPembelianDetail->produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $diskon_satuan_type = Const_Umum::DISKON_TYPE_RP;
        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'faktur_pembelian_detail_id' => $this->input_faktur_pembelian_detail_id,
            'produk_id' => $produk->id,
            'satuan_id' => $satuan->id,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,

            'faktur_pembelian_id' => $fakturPembelianDetail->header?->id,
            'faktur_pembelian_kode' => $fakturPembelianDetail->header?->kode,
            'faktur_pembelian_supplier_kode' => $fakturPembelianDetail->header?->kode_faktur_supplier,
            'tanggal_faktur' => $this->input_tanggal_faktur,
            'produk_nama' => $produk->nama,
            'satuan_nama' => $satuan->nama,
        ];

        $this->reset(
            'input_faktur_pembelian_id',
            'input_faktur_pembelian_detail_id',
            'input_satuan_id',
            'input_jumlah',
            'input_tanggal_faktur',
            'input_harga_satuan',
            'input_diskon_satuan',
            'index_edit_item',
        );
        $this->updatedInputFakturPembelianId();
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_faktur_pembelian_id = $item['faktur_pembelian_id'];
        $this->updatedInputFakturPembelianId();
        $this->input_faktur_pembelian_detail_id = $item['faktur_pembelian_detail_id'];
        $this->updatedInputFakturPembelianDetailId();
        $this->input_satuan_id = $item['satuan_id'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_tanggal_faktur = $item['tanggal_faktur'];
        $this->input_harga_satuan = $item['harga_satuan'];
        $this->input_diskon_satuan = $item['diskon_satuan'];

        $this->dispatch('set_value_dropdown_input_faktur_pembelian_detail_id', $this->input_faktur_pembelian_detail_id);
        $this->dispatch('set_value_dropdown_input_satuan_id', $this->input_satuan_id);
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        ReturPembelianService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        $this->calculateFooter();

        return view('admin.pembelian.retur-pembelian.edit')
            ->layout($this->layout);
    }
}
