<?php

namespace App\Livewire\Admin\Master\Cabang;

use Livewire\Component;
use App\Models\Master\Cabang;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use App\Traits\Livewire\WithEditForm;
use App\Services\Master\CabangService;

class Edit extends Component
{
    use WithEditForm;
    use WithFileUploads;

    public $model = Cabang::class;
    public $menuTitle = 'Cabang';
    public Cabang $obj;
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
    public $is_pkp;
    public $is_include_ppn;
    public $ppn_percent = 2;
    public $status;
    public $input_logo;
    public $input_ktp_foto;
    public $input_npwp_foto;
    public $input_sia_foto;
    public $input_sipa_foto;

    protected function rules(): array
    {
        return [
            'kode' => ['required', 'unique:cabangs,kode,' . $this->obj->id],
            'nama' => [
                'string', 'required',
                Rule::unique(Cabang::getTableName(), 'nama')
                    ->ignore($this->obj->id),
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
            'status' => ['required'],

            'input_logo' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_ktp_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_npwp_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_sia_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
            'input_sipa_foto' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
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
        $this->ktp_nama = $this->obj->ktp_nama;
        $this->ktp_nomor = $this->obj->ktp_nomor;
        $this->npwp_nama = $this->obj->npwp_nama;
        $this->npwp_nomor = $this->obj->npwp_nomor;
        $this->sia_nama = $this->obj->sia_nama;
        $this->sia_nomor = $this->obj->sia_nomor;
        $this->sia_berlaku = $this->obj->sia_berlaku;
        $this->sipa_nama = $this->obj->sipa_nama;
        $this->sipa_nomor = $this->obj->sipa_nomor;
        $this->sipa_berlaku = $this->obj->sipa_berlaku;
        $this->is_pkp = $this->obj->is_pkp ? true : false;
        $this->is_include_ppn = $this->obj->is_include_ppn ? true : false;
        $this->ppn_percent = $this->obj->ppn_percent;
        $this->status = $this->obj->status;
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

    public function submit($validated)
    {
        CabangService::update($this->obj, $validated);

        return $this->obj;
    }

    public function render()
    {
        return view('admin.master.cabang.edit')
            ->layout($this->layout);
    }
}
