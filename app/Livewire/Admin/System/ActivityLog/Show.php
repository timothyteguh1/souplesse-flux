<?php

namespace App\Livewire\Admin\System\ActivityLog;

use App\Models\Activity;
use App\Traits\Livewire\WithShowForm;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Show extends Component
{
    use WithShowForm;

    public $model = Activity::class;
    public $menuTitle = 'Activity Log';
    public Activity $obj;
    public $old;
    public $new;

    public function mount()
    {
        abort_if(Gate::none([$this->model::permissionShow()]), Response::HTTP_FORBIDDEN);
    }

    public function render()
    {
        $this->old = $this->obj->properties['old'] ?? [];
        $this->new = $this->obj->properties['attributes'] ?? [];

        // hide creditial attributes
        $hideAttributes = ['password'];
        foreach ($hideAttributes as $attribute) {
            unset($this->old[$attribute]);
            unset($this->new[$attribute]);
        }

        return view('admin.system.activity-log.show')
            ->layout($this->layout);
    }
}
