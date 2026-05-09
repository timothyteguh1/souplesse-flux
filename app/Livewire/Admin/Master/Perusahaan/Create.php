<?php

namespace App\Livewire\Admin\Master\Perusahaan;

use Livewire\Component;
use App\Models\Master\Perusahaan;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Services\Master\PerusahaanService;
use App\Traits\Livewire\WithCreateForm;

class Create extends Component
{
    use WithCreateForm;
    use WithFileUploads;

    public $model = Perusahaan::class;
    public $menuTitle = 'Perusahaan';
    public $kode;
    public $nama;
    public $alamat;
    public $kota;
    public $telp;
    public $email;
    public $logo;
    public $plan_id;
    public $user_name;
    public $user_email;
    public $user_username;
    public $user_password;

    protected function rules(): array
    {
        return [
            'kode' => ['required', 'unique:perusahaans,kode'],
            'nama' => [
                'string', 'required',
                Rule::unique(Perusahaan::getTableName(), 'nama'),
            ],
            'alamat' => [],
            'kota' => [],
            'telp' => [],
            'email' => [],
            'plan_id' => ['required'],

            'user_name' => ['required'],
            'user_email' => [],
            'user_username' => ['required'],
            'user_password' => ['required'],

            'logo' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
        ];
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
    }

    public function submit($validated)
    {
        return PerusahaanService::create($validated);
    }

    public function render()
    {
        return view('admin.master.perusahaan.create')->layout($this->layout);
    }
}
