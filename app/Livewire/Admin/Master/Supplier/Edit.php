<?php

namespace App\Livewire\Admin\Master\Supplier;

use Livewire\Component;
use App\Models\Master\Supplier;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\SupplierService;

class Edit extends Component
{
    use WithEditForm;

    public $model = Supplier::class;
    public $menuTitle = 'Supplier';
    public Supplier $obj;
    public $nama;
    public $telp;
    public $handphone;
    public $email;

    public $alamat;
    public $kota;

    public bool $is_pkp;
    public bool $is_include_ppn;

    public $jatuh_tempo;
    public $rekening_bank;
    public $rekening_nomor;
    public $rekening_nama;
    public $npwp;

    public $payment_info;
    public $keterangan;
    public $status;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string',
                'required',
                Rule::unique(Supplier::getTableName(), 'nama')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
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
            'status' => ['required'],
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

        $this->is_pkp = $this->obj->is_pkp;
        $this->is_include_ppn = $this->obj->is_include_ppn;

        $this->jatuh_tempo = $this->obj->jatuh_tempo;
        $this->rekening_bank = $this->obj->rekening_bank;
        $this->rekening_nomor = $this->obj->rekening_nomor;
        $this->rekening_nama = $this->obj->rekening_nama;
        $this->npwp = $this->obj->npwp;

        $this->payment_info = $this->obj->payment_info;
        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;
    }

    public function updatedIsPkp()
    {
        if (!$this->is_pkp) {
            $this->is_include_ppn = false;
        }
    }

    public function submit($validated)
    {
        SupplierService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.supplier.edit')
            ->layout($this->layout);
    }
}
