<?php

namespace App\Livewire\Admin\System\Dashboard;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('admin.system.dashboard.index')
            ->layout('admin.components.layouts.app');
    }
}
