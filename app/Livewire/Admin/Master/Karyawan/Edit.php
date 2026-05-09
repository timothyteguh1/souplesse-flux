<?php

namespace App\Livewire\Admin\Master\Karyawan;

use Livewire\Component;
use App\Models\Master\Karyawan;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\KaryawanService;

class Edit extends Component
{
    use WithEditForm;

    public $model = Karyawan::class;
    public $menuTitle = 'Salesman';
    public Karyawan $obj;
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
    public $status = 0;

    protected function rules(): array
    {
        return [
            'nama' => [
                'string',
                'required',
                Rule::unique(Karyawan::getTableName(), 'nama')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
            ],
            'user_id' => [],
            'no_ktp' => [
                'string',
                'required',
                Rule::unique(Karyawan::getTableName(), 'no_ktp')
                    ->where('cabang_id', $this->obj->cabang_id)
                    ->ignore($this->obj->id),
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
            'status' => ['required'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->nama = $this->obj->nama;
        $this->user_id = $this->obj->user_id;
        $this->no_ktp = $this->obj->no_ktp;
        $this->tanggal_masuk = $this->obj->tanggal_masuk;
        $this->level = $this->obj->level;
        $this->komisi = $this->obj->komisi;

        $this->telp = $this->obj->telp;
        $this->handphone = $this->obj->handphone;
        $this->email = $this->obj->telp;

        $this->alamat = $this->obj->alamat;
        $this->kota = $this->obj->kota;

        $this->keterangan = $this->obj->keterangan;
        $this->status = $this->obj->status;
    }

    public function submit($validated)
    {
        KaryawanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.karyawan.edit')
            ->layout($this->layout);
    }
}
