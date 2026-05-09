<?php

namespace App\Livewire\Admin\System\ActivityLog;

use App\Models\Activity;
use App\Models\User;
use App\Traits\HasKeywordSearch;
use App\Traits\Livewire\WithIndexForm;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\QueryHelpers\QH_DateTime;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Index extends Component
{
    use HasKeywordSearch;
    use WithIndexForm;

    public $model = Activity::class;
    public $menuTitle = 'Activity Log';
    protected $export_filename = 'activity-log';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.system.activity-log.index-export';
    public $user_id = '';
    public $tanggal;
    public $event;
    public $description;

    public function mount()
    {
        abort_if(Gate::none([$this->model::permissionIndex()]), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_datetime_range();
        $this->sortField = 'created_at';
    }

    private function getQuery()
    {
        return Activity::query()
            ->with('causer', 'subject')
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateTimeRange($query, $this->tanggal, 'created_at');
            })
            ->when($this->description, function ($query) {
                return $this->scopeKeywordSearch($query, $this->description, ['description', 'properties']);
            })
            ->when($this->user_id, function ($query) {
                return $query->causedBy(User::find($this->user_id));
            })
            ->when($this->event, function ($query) {
                return $query->forEvent($this->event);
            })
            ->orderBy('created_at', 'desc');
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.activity-log.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
