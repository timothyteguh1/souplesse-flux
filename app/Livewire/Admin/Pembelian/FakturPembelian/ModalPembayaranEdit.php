<?php

namespace App\Livewire\Admin\Pembelian\FakturPembelian;

use App\Models\Master\Kas;
use App\Models\Pembelian\FakturPembelian;
use App\Services\Pembelian\FakturPembelianService;
use App\Traits\Livewire\WithModalForm;
use App\Utilities\SelectHelpers\Master\SH_Kas;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ModalPembayaranEdit extends Component
{
    use WithModalForm;

    public FakturPembelian $obj;
    public $transaksi = [];
    public $grandtotal;
    public $total_bayar;
    public $kembalian;
    public $items = [];
    public $input_kas_id;
    public $input_keterangan;
    public $input_nominal = 0;
    public $index_edit_item = null;
    protected $listeners = [
        'refreshInfo' => 'refreshInfo',
    ];

    protected function rules(): array
    {
        return [
            'transaksi' => ['required'],
            'items' => ['array'],
            'total_bayar' => ['same:grandtotal'],
        ];
    }

    public function refreshInfo($params)
    {
        $obj_id = $params['obj_id'];
        $this->obj = FakturPembelian::find($obj_id);

        $this->transaksi = $params['transaksi'];

        // $this->grandtotal = collect($this->transaksi['items'])->sum(function ($item) {
        //     return $item['jumlah'] * ($item['harga_satuan'] - $item['diskon_satuan']);
        // });
        $this->grandtotal = $params['grandtotal'];

        $this->reset('input_kas_id', 'input_keterangan', 'input_nominal', 'items', 'index_edit_item');
        $this->refreshInputKasId();

        foreach ($this->obj->pembayarans()->with(['kas'])->get() as $item) {
            $this->items[] = [
                'id' => $item->id,
                'kas_id' => $item->kas_id,
                'kas_nama' => $item->kas->kode . ' - ' . $item->kas->nama,
                'keterangan' => $item->keterangan,
                'jumlah' => $item->jumlah,
            ];
        }

        $this->showModal();
    }

    public function refreshInputKasId()
    {
        $options = SH_Kas::user();

        $this->dispatch('refresh_dropdown_input_kas_id', [
            'options' => $options,
            'value' => null,
        ]);
    }

    public function addItem()
    {
        $validated = $this->validate([
            'input_kas_id' => ['required'],
            'input_keterangan' => [],
            'input_nominal' => ['required'],
        ]);

        $kas = Kas::find($validated['input_kas_id']);

        $this->items[] = [
            'id' => null,
            'kas_id' => $kas->id,
            'kas_nama' => $kas->kode . ' - ' . $kas->nama,
            'keterangan' => $validated['input_keterangan'],
            'jumlah' => $validated['input_nominal'],
        ];

        $this->reset('input_kas_id', 'input_keterangan', 'input_nominal');
    }

    public function editItem()
    {
        $validated = $this->validate([
            'input_kas_id' => ['required'],
            'input_keterangan' => [],
            'input_nominal' => ['required'],
        ]);

        $kas = Kas::find($validated['input_kas_id']);

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'kas_id' => $kas->id,
            'kas_nama' => $kas->kode . ' - ' . $kas->nama,
            'keterangan' => $validated['input_keterangan'],
            'jumlah' => $validated['input_nominal'],
        ];

        $this->reset('input_kas_id', 'input_keterangan', 'input_nominal', 'index_edit_item');
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_kas_id = $item['kas_id'];
        $this->input_keterangan = $item['keterangan'];
        $this->input_nominal = $item['jumlah'];
    }

    public function calculateFooter()
    {
        $this->total_bayar = collect($this->items)->sum('jumlah');
        $this->kembalian = $this->total_bayar - $this->grandtotal;

        if ($this->kembalian < 0) {
            $this->input_nominal = abs($this->kembalian);
        }
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            DB::beginTransaction();
            $data = array_merge($validated['transaksi'], [
                'items_pembayaran' => $validated['items'],
            ]);

            FakturPembelianService::update($this->obj, $data);
            DB::commit();

            session()->flash('flash_success', 'Faktur Pembelian  telah diubah.');
            return to_route('admin.pembelian.faktur-pembelian.show', $this->obj);
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.pembelian.faktur-pembelian.modal-pembayaran-edit');
    }
}
