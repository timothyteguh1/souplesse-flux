<?php

namespace App\Livewire\Admin\System\MutasiTransaksi;

use App\Models\Activity;
use App\Models\System\MutasiTransaksi;
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
    public $menuTitle = 'Mutasi Transaksi';
    protected $export_filename = 'mutasi_transaksi';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.system.mutasi-transaksi.index-export';
    public $cabang_ids;
    public $tanggal;
    public $jenis;
    public $jenis_transaksi;
    public $keterangan;

    public function mount()
    {
        abort_if(Gate::none([$this->model::permissionIndex()]), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_datetime_range();
        $this->sortField = 'created_at';
        $this->cabang_ids = session()->get('cabang_ids');
    }

    private function getQuery()
    {
        return MutasiTransaksi::query()
            ->with('cabang', 'vendor', 'header')
            ->whereIn('cabang_id', $this->cabang_ids)
            ->keywordSearch($this->jenis, ['jenis'])
            ->keywordSearch($this->jenis_transaksi, ['jenis_transaksi'])
            ->keywordSearch($this->keterangan, ['keterangan'])
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateTimeRange($query, $this->tanggal, 'created_at');
            })
            ->orderBy('created_at', 'desc');
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.mutasi-transaksi.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
