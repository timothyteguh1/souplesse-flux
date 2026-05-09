<?php

namespace App\Livewire\Admin\System\Billing;

use App\Models\Billing;
use Livewire\Component;
use App\Services\BillingService;
use App\Traits\Livewire\WithEditForm;
use App\Utilities\Constants\Const_Umum;

class Edit extends Component
{
    use WithEditForm;

    public $model = Billing::class;
    public $menuTitle = 'Billing';
    public Billing $obj;
    public $tanggal;
    public $tanggal_jatuh_tempo;
    public $perusahaan_id;
    public bool $is_pkp = true;
    public bool $is_include_ppn = true;
    public $ppn_percent;
    public $diskon_type = Const_Umum::DISKON_TYPE_RP;
    public $diskon;
    public $beban_lain;
    public $keterangan;
    public $items = [];
    public $input_item;
    public $input_jumlah;
    public $input_harga_satuan;
    public $input_diskon_satuan_type = Const_Umum::DISKON_TYPE_RP;
    public $input_diskon_satuan;
    public $index_edit_item = null;
    public $total = 0;
    public $status = 0;

    protected function rules(): array
    {
        return [
            'tanggal' => ['required'],
            'tanggal_jatuh_tempo' => ['required'],
            'perusahaan_id' => ['required'],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => ['nullable', 'numeric'],
            'diskon_type' => [],
            'diskon' => ['nullable', 'numeric'],
            'beban_lain' => ['nullable', 'numeric'],
            'keterangan' => [],

            'items' => ['required', 'array'],
            'items.*.id' => [],
            'items.*.item' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->tanggal = $this->obj->tanggal;
        $this->tanggal_jatuh_tempo = $this->obj->tanggal_jatuh_tempo;
        $this->perusahaan_id = $this->obj->perusahaan_id;
        $this->is_pkp = $this->obj->is_pkp;
        $this->is_include_ppn = $this->obj->is_include_ppn;
        $this->ppn_percent = $this->obj->ppn_percent;
        $this->diskon_type = $this->obj->diskon_type;
        $this->diskon = $this->obj->diskon;
        $this->beban_lain = $this->obj->beban_lain;
        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;

        foreach ($this->obj->details as $detail) {
            $this->items[] = [
                'id' => $detail->id,
                'item' => $detail->item,
                'jumlah' => $detail->jumlah,
                'harga_satuan' => $detail->harga_satuan,
                'diskon_satuan_type' => $detail->diskon_satuan_type,
                'diskon_satuan' => $detail->diskon_satuan,
                'diskon_satuan_rupiah' => $detail->diskon_satuan_rupiah,
                'diskon_satuan_persen' => $detail->diskon_satuan_persen,
                'subtotal' => $detail->subtotal,
            ];
        }
    }

    public function addItem()
    {
        $this->validate([
            'input_item' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:1'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan_type' => [],
            'input_diskon_satuan' => [],
        ]);

        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $diskon_satuan_type = $this->input_diskon_satuan ? $this->input_diskon_satuan_type : null;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;
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

        $subtotal = $jumlah * ($harga_satuan - $diskon_satuan_rupiah);

        $this->items[] = [
            'id' => null,
            'item' => $this->input_item,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,
            'diskon_satuan_rupiah' => $diskon_satuan_rupiah,
            'diskon_satuan_persen' => $diskon_satuan_persen,
            'subtotal' => $subtotal,
        ];

        $this->reset('input_item', 'input_jumlah', 'input_harga_satuan', 'input_diskon_satuan');
    }

    public function editItem()
    {
        $this->validate([
            'input_item' => ['required'],
            'input_jumlah' => ['required', 'numeric', 'min:1'],
            'input_harga_satuan' => ['required'],
            'input_diskon_satuan_type' => [],
            'input_diskon_satuan' => [],
        ]);

        $diskon_satuan = $this->input_diskon_satuan ?: 0;
        $diskon_satuan_type = $this->input_diskon_satuan ? $this->input_diskon_satuan_type : null;
        $jumlah = $this->input_jumlah;
        $harga_satuan = $this->input_harga_satuan;
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

        $subtotal = $jumlah * ($harga_satuan - $diskon_satuan_rupiah);

        $this->items[$this->index_edit_item] = [
            'id' => $this->items[$this->index_edit_item]['id'],
            'item' => $this->input_item,
            'jumlah' => $jumlah,
            'harga_satuan' => $harga_satuan,
            'diskon_satuan' => $diskon_satuan,
            'diskon_satuan_type' => $diskon_satuan_type,
            'diskon_satuan_rupiah' => $diskon_satuan_rupiah,
            'diskon_satuan_persen' => $diskon_satuan_persen,
            'subtotal' => $subtotal,
        ];

        $this->reset('input_item', 'input_jumlah', 'input_harga_satuan', 'input_diskon_satuan');
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_item = $item['item'];
        $this->input_jumlah = $item['jumlah'];
        $this->input_harga_satuan = $item['harga_satuan'];
        $this->input_diskon_satuan_type = $item['diskon_satuan_type'] ?: Const_Umum::DISKON_TYPE_RP;
        $this->input_diskon_satuan = $item['diskon_satuan'];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        BillingService::update($this->obj, $validated);

        return $this->obj;
    }

    public function calculateFooter()
    {
        $this->total = collect($this->items)->sum('subtotal');
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.system.billing.edit')
            ->layout($this->layout);
    }
}
