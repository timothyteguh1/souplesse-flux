<?php

namespace App\Livewire\Admin\Penjualan\SuratJalan;

use Livewire\Component;
use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Models\Master\Customer;
use Livewire\Attributes\Computed;
use App\Models\Penjualan\SuratJalan;
use App\Traits\Livewire\WithEditForm;
use App\Services\Penjualan\SuratJalanService;
use App\Models\Penjualan\PesananPenjualanDetail;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use App\Utilities\SelectHelpers\Master\SH_Customer;
use App\Utilities\SelectHelpers\Master\SH_Ekspedisi;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_PesananPenjualan;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_PesananPenjualanDetail;

class Edit extends Component
{
    use WithEditForm;

    public $model = SuratJalan::class;
    public $menuTitle = 'Surat Jalan';
    public SuratJalan $obj;
    public $jenis_transaksi;
    public $kode;
    public $tanggal;
    public $customer_id;
    public $gudang_id;
    public $ekspedisi_id;
    public $no_polisi;
    public $keterangan;
    public $alamat;
    public $kota;
    public $kode_pos;
    public $provinsi;
    public bool $is_pkp = false;
    public bool $is_include_ppn = false;
    public $ppn_percent;
    public $items = [];
    public $input_pesanan_penjualan_id;
    public $input_produk_id;
    public $input_satuan_id;
    public $input_satuan_nama;
    public $input_jumlah;
    public $input_jumlah_koli;
    public $input_expired_date;
    public $input_no_batch;
    public $index_edit_item = null;
    protected $listeners = [
        'refreshDataCustomer',
        'refreshDataProduk',
    ];

    protected function rules(): array
    {
        return [
            'jenis_transaksi' => ['required'],
            'tanggal' => ['required'],
            'customer_id' => ['required'],
            'gudang_id' => [],
            'ekspedisi_id' => ['required'],
            'no_polisi' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.id' => [],
            'items.*.pesanan_penjualan_detail_id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            // 'items.*.expired_date' => [],
            // 'items.*.no_batch' => [],
            'items.*.jumlah' => [],
            'items.*.jumlah_koli' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->jenis_transaksi = $this->obj->jenis_transaksi;
        $this->tanggal = $this->obj->tanggal;
        $this->customer_id = $this->obj->customer_id;
        $this->ekspedisi_id = $this->obj->ekspedisi_id;
        $this->no_polisi = $this->obj->no_polisi;
        $this->gudang_id = $this->obj->gudang_id;
        // $this->is_pkp = $this->obj->is_pkp;
        // $this->is_include_ppn = $this->obj->is_include_ppn;
        // $this->ppn_percent = $this->obj->ppn_percent;
        $this->keterangan = $this->obj->keterangan;
        $this->alamat = $this->obj->customer->alamat;
        $this->kota = $this->obj->customer->kota;
        $this->kode_pos = $this->obj->customer->kode_pos;
        $this->provinsi = $this->obj->customer->provinsi;

        $details = $this->obj->details()->with(['produk', 'satuan', 'pesananPenjualanDetail.header'])->get();
        $this->items = [];

        foreach ($details as $detail) {
            $this->items[] = [
                'id' => $detail->id,
                'pesanan_penjualan_detail_id' =>  $detail->pesanan_penjualan_detail_id,
                'pesanan_penjualan_id' =>  $detail->pesananPenjualanDetail->header->id,
                'pesanan_penjualan_kode' =>  $detail->pesananPenjualanDetail->header->kode,
                'produk_id' => $detail->produk_id,
                'produk_nama' => $detail->produk->nama,
                'satuan_id' => optional($detail->satuan)->id,
                'satuan_nama' => optional($detail->satuan)->nama,
                'jumlah' => $detail->jumlah,
                'jumlah_koli' => $detail->jumlah_koli,
                'expired_date' => $detail->expired_date,
                'no_batch' => $detail->no_batch,
            ];
        }
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
    public function optionsEkspedisiId()
    {
        return SH_Ekspedisi::active();
    }

    #[Computed(persist: true)]
    public function optionsInputPesananPenjualanId()
    {
        return SH_PesananPenjualan::belumSelesai(
            $this->customer_id,
            collect($this->items)->pluck('pesanan_penjualan_id'),
        );
    }

    public function refreshDataProduk($params)
    {
        $new_id = $params['new_id'];

        $options = SH_PesananPenjualanDetail::suratJalan($this->input_pesanan_penjualan_id, collect($this->items)->pluck('pesanan_penjualan_detail_id'));
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

    public function updatedCustomerId()
    {
        $customer = Customer::find($this->customer_id);
        $this->reset(['items', 'alamat', 'kota', 'kode_pos', 'provinsi', 'is_pkp', 'is_include_ppn']);

        if ($customer) {
            $this->alamat = optional($customer)->alamat;
            $this->kota = optional($customer)->kota;
            $this->kode_pos = optional($customer)->kode_pos;
            $this->provinsi = optional($customer)->provinsi;
        }

        $options = SH_PesananPenjualan::belumSelesai($this->customer_id);
        $this->dispatch('refresh_dropdown_input_pesanan_penjualan_id', [
            'options' => $options,
            'value' => null,
        ]);
    }

    public function updatedInputPesananPenjualanId()
    {
        $this->reset('input_produk_id', 'input_satuan_id', 'input_jumlah');
        if ($this->input_pesanan_penjualan_id) {
            $options = SH_PesananPenjualanDetail::suratJalan($this->input_pesanan_penjualan_id, collect($this->items)->pluck('pesanan_penjualan_detail_id'));
            $this->dispatch('refresh_dropdown_input_produk_id', [
                'options' => $options,
                'value' => null,
            ]);
        }
    }

    public function updatedInputProdukId()
    {
        $pesananPenjualanDetail = PesananPenjualanDetail::find($this->input_produk_id);
        $produk = Produk::find($pesananPenjualanDetail?->produk_id);
        $this->input_jumlah = $pesananPenjualanDetail?->sisa_faktur;

        if (!$produk) {
            $this->reset('input_satuan_id', 'input_satuan_nama', 'input_jumlah_koli');
            return;
        }

        $this->input_satuan_id = $pesananPenjualanDetail->satuan_id;
        $this->input_satuan_nama = $pesananPenjualanDetail->satuan->nama;
    }

    public function addItem()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_pesanan_penjualan_id' => ['required'],
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_satuan_nama' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
            'input_jumlah_koli' => ['required', 'numeric', 'min:0'],
            'input_expired_date' => [],
            'input_no_batch' => [],
        ]);

        $pesananPenjualanDetail = PesananPenjualanDetail::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $jumlah = $this->input_jumlah;
        $expired_date = $this->input_expired_date;
        $no_batch = $this->input_no_batch;
        $jumlah_koli = $this->input_jumlah_koli;

        $this->items[] = [
            'id' => null,
            'pesanan_penjualan_detail_id' => $pesananPenjualanDetail->id,
            'pesanan_penjualan_id' => $pesananPenjualanDetail->header->id,
            'pesanan_penjualan_kode' => $pesananPenjualanDetail->header->kode,
            'produk_id' => $pesananPenjualanDetail->produk_id,
            'produk_nama' => $pesananPenjualanDetail->produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'jumlah' => $jumlah,
            'jumlah_koli' => $jumlah_koli,

            'expired_date' => $expired_date,
            'no_batch' => $no_batch,
        ];

        $this->reset('input_pesanan_penjualan_id', 'input_produk_id', 'input_satuan_id', 'input_satuan_nama', 'input_jumlah', 'input_jumlah_koli');
    }

    public function editItem()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Customer harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_pesanan_penjualan_id' => ['required'],
            'input_produk_id' => ['required'],
            'input_satuan_id' => ['required'],
            'input_satuan_nama' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
            'input_jumlah_koli' => ['required', 'numeric', 'min:0'],
            'input_expired_date' => [],
            'input_no_batch' => [],
        ]);

        $pesananPenjualanDetail = PesananPenjualanDetail::find($this->input_produk_id);
        $satuan = Satuan::find($this->input_satuan_id);
        $jumlah = $this->input_jumlah;
        $jumlah_koli = $this->input_jumlah_koli;
        $expired_date = $this->input_expired_date;
        $no_batch = $this->input_no_batch;

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'pesanan_penjualan_detail_id' => $pesananPenjualanDetail->id,
            'pesanan_penjualan_id' => $pesananPenjualanDetail->header->id,
            'pesanan_penjualan_kode' => $pesananPenjualanDetail->header->kode,
            'produk_id' => $pesananPenjualanDetail->produk_id,
            'produk_nama' => $pesananPenjualanDetail->produk->nama,
            'satuan_id' => $satuan->id,
            'satuan_nama' => $satuan->nama,
            'jumlah' => $jumlah,
            'jumlah_koli' => $jumlah_koli,

            'expired_date' => $expired_date,
            'no_batch' => $no_batch,
        ];

        $this->reset('input_pesanan_penjualan_id', 'input_produk_id', 'input_satuan_id', 'input_satuan_nama', 'input_jumlah', 'input_jumlah_koli', 'index_edit_item');
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_pesanan_penjualan_id = $item['pesanan_penjualan_id'];
        $this->updatedInputPesananPenjualanId();
        $this->input_produk_id = $item['pesanan_penjualan_detail_id'];
        $this->updatedInputProdukId();

        $this->input_satuan_id = $item['satuan_id'];
        $this->input_satuan_nama = $item['satuan_nama'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_jumlah_koli = $item['jumlah_koli'];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        SuratJalanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.penjualan.surat-jalan.edit')
            ->layout($this->layout);
    }
}
