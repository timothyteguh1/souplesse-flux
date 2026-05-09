<?php

namespace App\Livewire\Admin\Master\Supplier;

use Livewire\Component;
use App\Models\Master\Supplier;
use App\Traits\Livewire\WithCreateForm;
use Illuminate\Validation\Rule;
use App\Services\Master\SupplierService;

class Create extends Component
{
    use WithCreateForm;

    public $model = Supplier::class;
    public $menuTitle = 'Supplier';
    public $cabang_id;
    public $kode;
    public $nama;
    public $telp;
    public $handphone;
    public $email;

    public $alamat;
    public $kota;

    public $is_pkp = true;
    public $is_include_ppn = false;

    public $jatuh_tempo = 0;
    public $rekening_bank;
    public $rekening_nomor;
    public $rekening_nama;
    public $npwp;

    public $payment_info;
    public $keterangan;


    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'nama' => [
                'string',
                'required',
                Rule::unique(Supplier::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'telp' => [],
            'handphone' => [],
            'email' => [],

            'alamat' => [],
            'kota' => [],

            'is_pkp' => [],
            'is_include_ppn' => [],

            'jatuh_tempo' => ['required'],
            'rekening_bank' => [],
            'rekening_nomor' => [],
            'rekening_nama' => [],
            'npwp' => [],

            'payment_info' => [],
            'keterangan' => [],
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

    public function submit($validated)
    {
        return SupplierService::create($validated);
    }

    public function render()
    {
        return view('admin.master.supplier.create')->layout($this->layout);
    }
}
