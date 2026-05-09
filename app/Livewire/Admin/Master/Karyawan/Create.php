<?php

namespace App\Livewire\Admin\Master\Karyawan;

use Livewire\Component;
use App\Models\Master\Karyawan;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithCreateForm;
use App\Services\Master\KaryawanService;

class Create extends Component
{
    use WithCreateForm;

    public $model = Karyawan::class;
    public $menuTitle = 'Salesman';
    public $cabang_id;
    public $kode;
    public $nama;
    public $user_id;
    public $no_ktp;
    public $tanggal_masuk;
    public $level;
    public $komisi;

    public $telp;
    public $handphone;
    public $email;

    public $alamat;
    public $kota;

    public $keterangan;

    protected function rules(): array
    {
        return [
            'cabang_id' => ['required'],
            'kode' => [],
            'nama' => [
                'string',
                'required',
                Rule::unique(Karyawan::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'user_id' => [],
            'no_ktp' => [
                'string',
                'nullable',
                Rule::unique(Karyawan::getTableName(), 'no_ktp')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'tanggal_masuk' => [],
            'level' => [],
            'komisi' => [],

            'telp' => [],
            'handphone' => [],
            'email' => [],

            'alamat' => [],
            'kota' => [],

            'keterangan' => [],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
        $this->cabang_id = session()->get('cabang_id');
    }

    public function submit($validated)
    {
        return KaryawanService::create($validated);
    }

    public function render()
    {
        return view('admin.master.karyawan.create')->layout($this->layout);
    }
}
