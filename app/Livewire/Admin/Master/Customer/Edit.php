<?php

namespace App\Livewire\Admin\Master\Customer;

use Livewire\Component;
use App\Models\Master\Customer;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\CustomerService;

class Edit extends Component
{
    use WithEditForm;

    public $model = Customer::class;
    public $menuTitle = 'Customer';
    public Customer $obj;
    public $nama;

    public $telp;
    public $handphone;
    public $email;
    public $alamat;
    public $kota;

    public bool $is_blacklist;
    public bool $is_pkp;
    public bool $is_include_ppn;

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
    public $status;

    public $items = [];
    public $input_metode_pembayaran;
    public $input_diskon;
    public $index_edit_item = null;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string',
                'required',
                Rule::unique(Customer::getTableName(), 'nama')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
            ],
            'telp' => [
                'string',
                'nullable',
                Rule::unique(Customer::getTableName(), 'telp')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
            ],
            'handphone' => [
                'string',
                'nullable',
                Rule::unique(Customer::getTableName(), 'handphone')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
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
            'status' => ['required'],

            'items' => ['nullable', 'array'],
            'items.*.id' => [],
            'items.*.metode_pembayaran' => [],
            'items.*.diskon' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->nama = $this->obj->nama;
        $this->telp = $this->obj->telp;
        $this->handphone = $this->obj->handphone;
        $this->email = $this->obj->email;
        $this->alamat = $this->obj->alamat;
        $this->kota = $this->obj->kota;

        $this->is_blacklist = $this->obj->is_blacklist;
        $this->is_pkp = $this->obj->is_pkp;
        $this->is_include_ppn = $this->obj->is_include_ppn;

        $this->npwp_kode = $this->obj->npwp_kode;
        $this->npwp_nik = $this->obj->npwp_nik;
        $this->npwp_wajib_pajak = $this->obj->npwp_wajib_pajak;
        $this->npwp_blok = $this->obj->npwp_blok;
        $this->npwp_nomor = $this->obj->npwp_nomor;
        $this->npwp_alamat = $this->obj->npwp_alamat;
        $this->npwp_kota = $this->obj->npwp_kota;
        $this->npwp_kode_pos = $this->obj->npwp_kode_pos;
        $this->npwp_provinsi = $this->obj->npwp_provinsi;
        $this->npwp_negara = $this->obj->npwp_negara;

        $this->jatuh_tempo = $this->obj->jatuh_tempo;
        $this->limit_piutang = $this->obj->limit_piutang;
        $this->rekening_bank = $this->obj->rekening_bank;
        $this->rekening_nama = $this->obj->rekening_nama;
        $this->rekening_nomor = $this->obj->rekening_nomor;
        $this->status = $this->obj->status;

        $details = $this->obj->customerDiskons;
        $this->items = [];

        foreach ($details as $detail) {
            $this->items[] = [
                'id' => $detail->id,
                'metode_pembayaran' => $detail->metode_pembayaran,
                'diskon' => $detail->diskon,
            ];
        }
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
            'id' => null,
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
            'id' => $this->items[$this->index_edit_item]['id'],
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
        CustomerService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.customer.edit')
            ->layout($this->layout);
    }
}
