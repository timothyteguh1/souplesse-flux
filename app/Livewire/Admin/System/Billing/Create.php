<?php

namespace App\Livewire\Admin\System\Billing;

use App\Models\Billing;
use Livewire\Component;
use App\Services\BillingService;
use App\Traits\Livewire\WithCreateForm;
use App\Utilities\Constants\Const_Umum;

class Create extends Component
{
    use WithCreateForm;

    public $model = Billing::class;
    public $menuTitle = 'Billing';
    public $kode;
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

    protected function rules(): array
    {
        return [
            'kode' => [],
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
            'items.*.item' => [],
            'items.*.jumlah' => [],
            'items.*.harga_satuan' => [],
            'items.*.diskon_satuan_type' => [],
            'items.*.diskon_satuan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->tanggal = _get_default_datetime();
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
        return BillingService::create($validated);
    }

    public function calculateFooter()
    {
        $this->total = collect($this->items)->sum('subtotal');
    }

    public function render()
    {
        $this->calculateFooter();
        return view('admin.system.billing.create')->layout($this->layout);
    }
}
