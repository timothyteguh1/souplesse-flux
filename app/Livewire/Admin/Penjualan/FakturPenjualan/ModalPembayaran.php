<?php

namespace App\Livewire\Admin\Penjualan\FakturPenjualan;

use App\Models\Master\Kas;
use App\Services\Penjualan\FakturPenjualanService;
use App\Traits\Livewire\WithModalForm;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ModalPembayaran extends Component
{
    use WithModalForm;

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
        $this->grandtotal = $params['grandtotal'];
        // $this->grandtotal = $params['transaksi']['grandtotal'];
        // unset($params['transaksi']['grandtotal']);
        $this->transaksi = $params['transaksi'];

        $this->reset('input_kas_id', 'input_keterangan', 'input_nominal', 'items', 'index_edit_item');
        $this->showModal();
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

            $obj = FakturPenjualanService::create($data);
            DB::commit();

            session()->flash('flash_success', 'Faktur Penjualan telah ditambahkan.');
            return to_route('admin.penjualan.faktur-penjualan.show', $obj);
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.penjualan.faktur-penjualan.modal-pembayaran');
    }
}
