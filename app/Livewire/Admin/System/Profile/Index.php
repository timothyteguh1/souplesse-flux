<?php

namespace App\Livewire\Admin\System\Profile;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('admin.system.profile.index')
            ->layout('admin.components.layouts.app');
    }
}
