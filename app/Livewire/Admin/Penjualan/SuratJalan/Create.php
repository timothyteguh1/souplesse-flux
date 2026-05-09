<?php

namespace App\Livewire\Admin\Penjualan\SuratJalan;

use Livewire\Component;
use Livewire\Attributes\Computed;
use App\Models\Penjualan\SuratJalan;
use App\Traits\Livewire\WithCreateForm;
use App\Models\Penjualan\PesananPenjualan;
use App\Services\Penjualan\SuratJalanService;
use App\Utilities\Constants\Const_JenisTransaksi;
use App\Utilities\SelectHelpers\Master\SH_Gudang;
use App\Utilities\SelectHelpers\Transaksi\Penjualan\SH_PesananPenjualan;

class Create extends Component
{
    use WithCreateForm;

    public $model = SuratJalan::class;
    public $menuTitle = 'Surat Jalan';
    public $jenis_transaksi = Const_JenisTransaksi::SURAT_JALAN;
    public $cabang_id;
    public $kode;
    public $tanggal;
    public $pesanan_penjualan_id;
    public $customer_id;
    public $customer_nama;
    public $gudang_id;
    public $no_polisi;
    public $keterangan;
    public $items = [];
    public $input_produk_nama;
    public $input_satuan_nama;
    public $input_jumlah;
    public $index_edit_item = null;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'jenis_transaksi' => ['required'],
            'tanggal' => ['required'],
            'pesanan_penjualan_id' => ['required'],
            'customer_id' => ['required'],
            'gudang_id' => ['required'],
            'no_polisi' => ['required'],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.pesanan_penjualan_detail_id' => [],
            'items.*.produk_id' => [],
            'items.*.satuan_id' => [],
            'items.*.jumlah' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();

        $this->tanggal = _get_default_datetime();
        $this->cabang_id = session()->get('cabang_id');
    }

    #[Computed(persist: true)]
    public function optionsPesananPenjualanId()
    {
        return SH_PesananPenjualan::belumDikirim();
    }

    #[Computed(persist: true)]
    public function optionsGudangId()
    {
        return SH_Gudang::user();
    }

    public function updatedPesananPenjualanId()
    {
        $this->reset('customer_id', 'customer_nama', 'items');
        if ($this->pesanan_penjualan_id) {
            $pesananPenjualan = PesananPenjualan::with(['details'])->find($this->pesanan_penjualan_id);
            $this->customer_id = $pesananPenjualan->customer_id;
            $this->customer_nama = $pesananPenjualan->customer->nama;

            $details = $pesananPenjualan->details()->with(['produk', 'satuan'])->get();
            foreach ($details as $detail) {
                $this->items[] = [
                    'pesanan_penjualan_detail_id' => $detail->id,
                    'produk_id' => $detail->produk_id,
                    'satuan_id' => $detail->satuan_id,
                    'jumlah' => $detail->jumlah,

                    'produk_nama' => $detail->produk->nama,
                    'satuan_nama' => $detail->satuan->nama,
                ];
            }
        }
    }

    public function editItem()
    {
        if (!$this->customer_id) {
            $this->addError('flash_danger', 'Pesanan Penjualan harus dipilih terlebih dahulu.');
            $this->dispatch('page-to-top');

            return;
        }

        $this->validate([
            'input_produk_nama' => ['required'],
            'input_satuan_nama' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:0'],
        ]);

        $jumlah = $this->input_jumlah;

        $this->items[$this->index_edit_item] = [
            'pesanan_penjualan_detail_id' => $this->items[$this->index_edit_item]['pesanan_penjualan_detail_id'],
            'produk_id' => $this->items[$this->index_edit_item]['produk_id'],
            'satuan_id' => $this->items[$this->index_edit_item]['satuan_id'],
            'jumlah' => $jumlah,

            'produk_nama' => $this->items[$this->index_edit_item]['produk_nama'],
            'satuan_nama' => $this->items[$this->index_edit_item]['satuan_nama'],
        ];

        $this->reset('input_produk_nama', 'input_satuan_nama', 'input_jumlah', 'index_edit_item');
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_produk_nama = $item['produk_nama'];
        $this->input_satuan_nama = $item['satuan_nama'];
        $this->input_jumlah = $item['jumlah'];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        return SuratJalanService::create($validated);
    }

    public function render()
    {
        return view('admin.penjualan.surat-jalan.create')->layout($this->layout);
    }
}
