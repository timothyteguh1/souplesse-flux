<?php

namespace App\Livewire\Admin\Master\Perusahaan;

use Livewire\Component;
use App\Models\Master\Perusahaan;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\PerusahaanService;

class Edit extends Component
{
    use WithEditForm;
    use WithFileUploads;

    public $model = Perusahaan::class;
    public $menuTitle = 'Perusahaan';
    public Perusahaan $obj;
    public $kode;
    public $nama;
    public $alamat;
    public $kota;
    public $telp;
    public $email;
    public $status;
    public $logo;
    public $plan_id;

    protected function rules(): array
    {
        return [
            'kode' => ['required', 'unique:perusahaans,kode,' . $this->obj->id],
            'nama' => [
                'string', 'required',
                Rule::unique(Perusahaan::getTableName(), 'nama')
                    ->ignore($this->obj->id),
            ],
            'alamat' => [],
            'kota' => [],
            'telp' => [],
            'email' => [],
            'status' => ['required'],
            'plan_id' => ['required'],

            'logo' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionEditGate();

        $this->kode = $this->obj->kode;
        $this->nama = $this->obj->nama;
        $this->alamat = $this->obj->alamat;
        $this->kota = $this->obj->kota;
        $this->telp = $this->obj->telp;
        $this->email = $this->obj->email;
        $this->status = $this->obj->status;
        $this->plan_id = $this->obj->plan_id;
    }

    public function submit($validated)
    {
        PerusahaanService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.perusahaan.edit')
            ->layout($this->layout);
    }
}
