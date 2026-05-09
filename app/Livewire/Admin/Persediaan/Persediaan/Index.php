<?php

namespace App\Livewire\Admin\Persediaan\Persediaan;

use App\Models\System\MutasiStok;
use App\Traits\Livewire\WithCustomForm;
use App\Utilities\Constants\Const_Umum;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class Index extends Component
{
    use WithCustomForm;

    public $menuTitle = 'Persediaan';
    protected $export_filename = 'persediaan';
    protected $export_orientation = Const_Umum::ORIENTATION_PORTRAIT;
    protected $export_view = 'admin.persediaan.persediaan.index-export';
    public $keyword;
    public $gudang_id;
    public $gudang_ids;
    public $gudang_ids_user;
    public $kategori_id;
    public $isDisplayZero = false;
    public $isDibawahStokMinimum = false;
    public $cabang_ids;

    public function mount()
    {
        abort_if(Gate::none(['admin.persediaan.persediaan.index']), Response::HTTP_FORBIDDEN);
        $this->cabang_ids = session()->get('cabang_ids');
        $this->gudang_ids_user = auth()->user()->getPermissionGudangIds();
    }

    private function getQuery()
    {
        $this->gudang_ids = $this->gudang_id ? [$this->gudang_id] : $this->gudang_ids_user;

        return MutasiStok::query()
            ->with(['gudang', 'produk.kategoriProduk', 'satuan', 'cabang'])
            ->join('produks', 'produks.id', '=', 'mutasi_stoks.produk_id')
            ->whereIn('mutasi_stoks.cabang_id', $this->cabang_ids)
            ->whereIn('mutasi_stoks.gudang_id', $this->gudang_ids)
            ->keywordSearch($this->keyword, ['produks.kode', 'produks.nama', 'mutasi_stoks.expired_date'])
            ->havingRaw('total != 0')
            ->when($this->kategori_id, function ($query) {
                return $query->where('produks.kategori_produk_id', $this->kategori_id);
            })
            ->when($this->isDibawahStokMinimum, function ($query) {
                return $query->havingRaw('total <= produks.stok_minimum');
            })
            ->select([
                'mutasi_stoks.cabang_id',
                'mutasi_stoks.gudang_id',
                'mutasi_stoks.produk_id',
                'mutasi_stoks.satuan_id',
                'mutasi_stoks.expired_date',
                'mutasi_stoks.no_batch',
                'produks.stok_minimum',
                DB::raw('SUM(jumlah) as total'),
            ])
            ->groupBy([
                'mutasi_stoks.cabang_id',
                'mutasi_stoks.gudang_id',
                'mutasi_stoks.produk_id',
                'mutasi_stoks.satuan_id',
                'mutasi_stoks.expired_date',
                'mutasi_stoks.no_batch',
                'produks.stok_minimum',
            ]);
    }

    public function render()
    {
        $this->processFilter();

        return view('admin.persediaan.persediaan.index', [
            'data' => $this->data,
        ])->layout($this->layout);
    }
}
