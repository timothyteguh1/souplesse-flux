<?php

namespace App\Livewire\Admin\System\MutasiStok;

use App\Models\Activity;
use App\Models\System\MutasiStok;
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
    public $menuTitle = 'Mutasi Stok';
    protected $export_filename = 'mutasi_stok';
    protected $export_orientation = Const_Umum::ORIENTATION_LANDSCAPE;
    protected $export_view = 'admin.system.mutasi-stok.index-export';
    public $cabang_ids;
    public $gudang_id;
    public $produk_id;
    public $satuan_transaksi_id;
    public $tanggal;
    public $jenis_transaksi;

    public function mount()
    {
        abort_if(Gate::none([$this->model::permissionIndex()]), Response::HTTP_FORBIDDEN);
        $this->tanggal = _get_default_datetime_range();
        $this->sortField = 'created_at';
        $this->cabang_ids = session()->get('cabang_ids');
    }

    private function getQuery()
    {
        return MutasiStok::query()
            ->with('cabang', 'header', 'gudang', 'produk', 'satuanTransaksi')
            ->whereIn('cabang_id', $this->cabang_ids)
            ->when($this->gudang_id, function ($query) {
                return $query->where('gudang_id', $this->gudang_id);
            })
            ->when($this->produk_id, function ($query) {
                return $query->where('produk_id', $this->produk_id);
            })
            ->when($this->satuan_transaksi_id, function ($query) {
                return $query->where('satuan_transaksi_id', $this->satuan_transaksi_id);
            })
            ->keywordSearch($this->jenis_transaksi, ['jenis_transaksi'])
            ->when($this->tanggal, function ($query) {
                return QH_DateTime::scopeDateTimeRange($query, $this->tanggal, 'created_at');
            })
            ->orderBy('created_at', 'desc');
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.system.mutasi-stok.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
