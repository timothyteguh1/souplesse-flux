<?php

namespace App\Livewire\Admin\Master\Customer;

use App\Models\Master\Customer;
use App\Services\Master\CustomerService;
use App\Traits\Livewire\WithModalForm;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ModalCreate extends Component
{
    use WithModalForm;

    public $model = Customer::class;
    public $form_id;
    public $cabang_id;
    public $kode;
    public $nama;
    public $telp;
    public $handphone;
    public $whatsapp;
    public $email;
    public $fax;
    public $website;
    public $alamat;
    public $kota;
    public $kode_pos;
    public $provinsi;
    public $jatuh_tempo = 0;
    public $limit_piutang = 0;
    public $items = [];
    protected $listeners = [
        'refreshInfo' => 'refreshInfo',
    ];

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
            'whatsapp' => [
                'string',
                'nullable',
                Rule::unique(Customer::getTableName(), 'whatsapp')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'email' => [],
            'fax' => [],
            'website' => [],
            'alamat' => [],
            'kota' => [],
            'kode_pos' => [],
            'provinsi' => [],
            'jatuh_tempo' => ['required'],
            'limit_piutang' => ['required', 'numeric', 'min:0'],

            'items' => ['nullable', 'array'],
        ];
    }

    public function refreshInfo($params = null)
    {
        if (!$this->checkPermissionCreate()) {
            return;
        }

        $this->reset([
            'cabang_id',
            'kode',
            'nama',
            'telp',
            'handphone',
            'whatsapp',
            'email',
            'fax',
            'website',
            'alamat',
            'kota',
            'kode_pos',
            'provinsi',
            'jatuh_tempo',
            'limit_piutang',
        ]);

        $this->cabang_id = session()->get('cabang_id');
        $this->form_id = $params['form_id'];
        $this->showModal($this->form_id);
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            DB::beginTransaction();
            $obj = CustomerService::create($validated);
            DB::commit();

            $this->dispatch('refreshDataCustomer', ['new_id' => $obj->id]);
            $this->closeModal($this->form_id);
        } catch (Exception $exception) {
            DB::rollBack();
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.master.customer.modal-create');
    }
}
