<?php

namespace App\Traits\Livewire;

use App\Exceptions\GeneralException;
use App\Exports\IndexExports;
use App\Utilities\Constants\Const_Umum;
use Excel;
use Livewire\WithPagination;
use PDF;

trait WithCustomForm
{
    use HasCheckPermissionGate;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    protected $layout = 'admin.components.layouts.custom';
    private $data;
    private $query;
    public $no_item = 1;
    public $perPage = 25;
    public $sortAsc = false;
    public $sortField = null;

    abstract private function getQuery();

    private function getData()
    {
        $query = $this->getQuery();

        if ($query instanceof \Illuminate\Database\Eloquent\Builder) {
            return $query->get();
        }

        if ($query instanceof \Illuminate\Database\Query\Builder) {
            return $query->get();
        }

        return $query;
    }

    public function sort($column, $toggleSort = true)
    {
        if ($this->sortField == $column && $toggleSort) {
            $this->toggleSort();
        }

        if ($this->sortField != $column) {
            $this->sortAsc = true;
        }

        $this->sortField = $column;

        $this->query = $this->getQuery()
            ->reorder()
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
    }

    private function toggleSort()
    {
        $this->sortAsc = ! $this->sortAsc;
    }

    public function processFilter()
    {
        if (! $this->query) {
            if ($this->sortField) {
                $this->sort($this->sortField, false);
            } else {
                $this->query = $this->getQuery();
            }
        }

        $this->data = $this->query->paginate($this->perPage);
        $page = $this->getPage();
        $this->no_item = ($page - 1) * $this->perPage + 1;

        if ($this->data->total() < $page * $this->perPage) {
            $this->resetPage();
            // TODO: Jika sudah pernah ke page besar dengan per page kecil, lalu ke per page besar, maka data akan kosong
        }
    }

    private function getParams()
    {
        return [];
    }

    private function getExportFilename($extension = 'xlsx')
    {
        if (empty($this->export_filename)) {
            return 'Export' . $extension;
        }

        return $this->export_filename . '.' . $extension;
    }

    private function getExportOrientation()
    {
        if (empty($this->export_orientation)) {
            return Const_Umum::ORIENTATION_PORTRAIT;
        }

        return $this->export_orientation;
    }

    private function getExportPaper()
    {
        if (empty($this->export_paper)) {
            return Const_Umum::PAPER_A4;
        }

        return $this->export_paper;
    }

    private function getExportView()
    {
        if (empty($this->export_view)) {
            throw new GeneralException('$export_view must be defined in ' . __CLASS__);
        }

        return $this->export_view;
    }

    public function exportExcel()
    {
        $view = $this->getExportView();
        $data = $this->getData();
        $params = $this->getParams();
        $filename = $this->getExportFilename('xlsx');
        $filename = _insert_timestamp_before_extension($filename);

        return Excel::download(new IndexExports($view, $data, $params, Const_Umum::FILETYPE_XLSX), $filename);
    }

    public function exportPdf()
    {
        $view = $this->getExportView();
        $data = $this->getData();
        $params = $this->getParams();
        $filename = $this->getExportFilename('pdf');
        $filename = _insert_timestamp_before_extension($filename);
        $orientation = $this->getExportOrientation();
        $paper = $this->getExportPaper();

        $pdf = PDF::loadView($view, ['data' => $data, 'params' => $params, 'file_type' => Const_Umum::FILETYPE_PDF])
            ->setPaper($paper)
            ->setOrientation($orientation);

        return $pdf->stream($filename);
    }
}
