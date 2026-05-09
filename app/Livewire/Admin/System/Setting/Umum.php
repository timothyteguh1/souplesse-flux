<?php

namespace App\Livewire\Admin\System\Setting;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Umum extends Component
{
    public $menuTitle = 'Umum';

    protected function rules(): array
    {
        return [
            //
        ];
    }

    public function mount()
    {
        abort_if(Gate::none(['admin.system.setting.umum']), Response::HTTP_FORBIDDEN);
    }

    public function submit()
    {
        //        $validated = $this->validate();

        //        try {
        //            //
        //        } catch (Exception $exception) {
        //            $this->addError('flash_danger', _get_exception_message($exception));
        //
        //            return false;
        //        }
    }

    public function render()
    {
        return view('admin.system.setting.umum')
            ->layout('admin.components.layouts.app');
    }
}
