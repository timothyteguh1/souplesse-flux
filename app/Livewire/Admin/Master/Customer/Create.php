<?php

namespace App\Livewire\Admin\Master\Customer;

use Livewire\Component;
use App\Models\Master\Customer;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithCreateForm;
use App\Services\Master\CustomerService;

class Create extends Component
{
    use WithCreateForm;

    public $model = Customer::class;
    public $menuTitle = 'Customer';
    public $cabang_id;
    public $kode;
    public $nama;

    public $telp;
    public $handphone;
    public $email;
    public $alamat;
    public $kota;

    public $is_blacklist = false;
    public $is_pkp = true;
    public $is_include_ppn = false;

    public $npwp_kode;
    public $npwp_nik;
    public $npwp_wajib_pajak;
    public $npwp_blok;
    public $npwp_nomor;
    public $npwp_alamat;
    public $npwp_kota;
    public $npwp_kode_pos;
    public $npwp_provinsi;
    public $npwp_negara;

    public $jatuh_tempo = 0;
    public $limit_piutang = 0;
    public $rekening_bank;
    public $rekening_nomor;
    public $rekening_nama;

    public $items = [];
    public $input_metode_pembayaran;
    public $input_diskon;
    public $index_edit_item = null;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'nama' => [
                'string',
                'required',
                Rule::unique(Customer::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'telp' => [
                'string',
                'nullable',
                Rule::unique(Customer::getTableName(), 'telp')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'handphone' => [
                'string',
                'nullable',
                Rule::unique(Customer::getTableName(), 'handphone')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'email' => [],
            'alamat' => [],
            'kota' => [],

            'is_blacklist' => [],
            'is_pkp' => [],
            'is_include_ppn' => [],

            'npwp_kode' => [],
            'npwp_nik' => [],
            'npwp_wajib_pajak' => [],
            'npwp_blok' => [],
            'npwp_nomor' => [],
            'npwp_alamat' => [],
            'npwp_kota' => [],
            'npwp_kode_pos' => [],
            'npwp_provinsi' => [],
            'npwp_negara' => [],

            'jatuh_tempo' => ['required'],
            'limit_piutang' => ['required', 'numeric', 'min:0'],
            'rekening_bank' => [],
            'rekening_nomor' => [],
            'rekening_nama' => [],

            'items' => ['nullable', 'array'],
            'items.*.metode_pembayaran' => [],
            'items.*.diskon' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function updatedIsPkp()
    {
        if (!$this->is_pkp) {
            $this->is_include_ppn = false;
        }
    }

    public function addItem()
    {

        $this->validate([
            'input_metode_pembayaran' => ['required'],
            'input_diskon' => ['required', 'numeric', 'min:0'],
        ]);

        $this->items[] = [
            'metode_pembayaran' => $this->input_metode_pembayaran,
            'diskon' => $this->input_diskon,
        ];

        $this->reset(
            'input_metode_pembayaran',
            'input_diskon',
        );
    }

    public function editItem()
    {

        $this->validate([
            'input_metode_pembayaran' => ['required'],
            'input_diskon' => ['required', 'numeric', 'min:0'],
        ]);

        $this->items[$this->index_edit_item] = [
            'metode_pembayaran' => $this->input_metode_pembayaran,
            'diskon' => $this->input_diskon,
        ];

        $this->reset(
            'input_metode_pembayaran',
            'input_diskon',
            'index_edit_item'
        );
    }

    public function edit($index)
    {
        $this->index_edit_item = $index;

        $item = $this->items[$index];
        $this->input_metode_pembayaran = $item['metode_pembayaran'];
        $this->input_diskon = $item['diskon'];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function submit($validated)
    {
        return CustomerService::create($validated);
    }

    public function render()
    {
        return view('admin.master.customer.create')->layout($this->layout);
    }
}
