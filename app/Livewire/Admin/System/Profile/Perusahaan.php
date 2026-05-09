<?php

namespace App\Livewire\Admin\System\Profile;

use App\Services\Master\PerusahaanService;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;

class Perusahaan extends Component
{
    use WithFileUploads;

    public $menuTitle = 'Perusahaan';
    public \App\Models\Master\Perusahaan $obj;
    public $nama;
    public $alamat;
    public $kota;
    public $telp;
    public $email;
    public $logo;
    public $plan_id;

    protected function rules(): array
    {
        return [
            'nama' => ['required'],
            'alamat' => ['required'],
            'kota' => ['required'],
            'telp' => ['required'],
            'email' => ['required'],
            'plan_id' => ['required'],

            'logo' => ['nullable', 'mimes:jpeg,png,gif,bmp,tiff,webp,heic,heif'],
        ];
    }

    public function mount()
    {
        abort_if(Gate::none(['admin.system.profile.perusahaan']), Response::HTTP_FORBIDDEN);

        $this->obj = auth()->user()->perusahaan;
        $this->nama = $this->obj->nama;
        $this->alamat = $this->obj->alamat;
        $this->kota = $this->obj->kota;
        $this->telp = $this->obj->telp;
        $this->email = $this->obj->email;
        $this->plan_id = $this->obj->plan_id;
    }

    public function submit()
    {
        $validated = $this->validate();

        try {
            PerusahaanService::update($this->obj, $validated);
            session()->flash('flash_success', $this->menuTitle . ' berhasil diupdate.');
        } catch (Exception $exception) {
            $this->addError('flash_danger', _get_exception_message($exception));

            return false;
        }
    }

    public function render()
    {
        return view('admin.system.profile.perusahaan')
            ->layout('admin.components.layouts.app');
    }
}
