<?php

namespace App\Livewire\Admin\Master\Cabang;

use Livewire\Component;
use App\Models\Master\Cabang;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Services\Master\CabangService;
use App\Traits\Livewire\WithCreateForm;

class Create extends Component
{
    use WithCreateForm;
    use WithFileUploads;

    public $model = Cabang::class;
    public $menuTitle = 'Cabang';
    public $kode;
    public $nama;
    public $alamat;
    public $kota;
    public $telp;
    public $email;
    public $ktp_nama;
    public $ktp_nomor;
    public $npwp_nama;
    public $npwp_nomor;
    public $sia_nama;
    public $sia_nomor;
    public $sia_berlaku;
    public $sipa_nama;
    public $sipa_nomor;
    public $sipa_berlaku;
    public $is_pkp = true;
    public $is_include_ppn = false;
    public $ppn_percent = 2;
    public $input_logo;
    public $input_ktp_foto;
    public $input_npwp_foto;
    public $input_sia_foto;
    public $input_sipa_foto;

    protected function rules(): array
    {
        return [
            'kode' => ['required', 'unique:cabangs,kode'],
            'nama' => [
                'string', 'required',
                Rule::unique(Cabang::getTableName(), 'nama'),
            ],
            'alamat' => [],
            'kota' => [],
            'telp' => [],
            'email' => [],
            'ktp_nama' => [],
            'ktp_nomor' => [],
            'npwp_nama' => [],
            'npwp_nomor' => [],
            'sia_nama' => [],
            'sia_nomor' => [],
            'sia_berlaku' => [],
            'sipa_nama' => [],
            'sipa_nomor' => [],
            'sipa_berlaku' => [],
            'is_pkp' => [],
            'is_include_ppn' => [],
            'ppn_percent' => [],

            'input_logo' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_ktp_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_npwp_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_sia_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_sipa_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
        ];
    }

    public function updatedIsPkp()
    {
        if (!$this->is_pkp) {
            $this->is_include_ppn = false;
            $this->ppn_percent = 0;
        } else {
            $this->ppn_percent = 2;
        }
    }

    public function mount()
    {
        $this->checkPermissionCreateGate();
    }

    public function submit($validated)
    {
        return CabangService::create($validated);
    }

    public function render()
    {
        return view('admin.master.cabang.create')->layout($this->layout);
    }
}
