<?php

namespace App\Traits\Livewire;

use App\Exceptions\GeneralException;
use App\Exports\ReportExports;
use App\Utilities\Constants\Const_Umum;
use Excel;
use PDF;

trait WithReportForm
{
    use HasCetakBrowser;

    protected $layout = 'admin.components.layouts.report';
    public $is_lihat = false;
    public $data = [];

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
        $this->makeData();

        $view = $this->getExportView();
        $filename = $this->getExportFilename('xlsx');
        $filename = _insert_timestamp_before_extension($filename);

        return Excel::download(new ReportExports($view, $this->data, Const_Umum::FILETYPE_XLSX), $filename);
    }

    public function exportPdf()
    {
        $this->makeData();

        $view = $this->getExportView();
        $filename = $this->getExportFilename('pdf');
        $filename = _insert_timestamp_before_extension($filename);
        $orientation = $this->getExportOrientation();
        $paper = $this->getExportPaper();

        $pdf = PDF::loadView($view, array_merge($this->data, ['file_type' => Const_Umum::FILETYPE_PDF]))
            ->setPaper($paper)
            ->setOrientation($orientation);

        return $pdf->stream($filename);
    }

    public function exportPrint()
    {
        $this->makeData();

        $view = view($this->export_view, array_merge($this->data, ['file_type' => Const_Umum::FILETYPE_WEB]));

        $this->cetakRawHtml($view->render());
    }

    public function prosesLihat()
    {
        $this->makeData();
    }

    private function makeData()
    {
        $this->is_lihat = false;
        $this->data = $this->getData();
        $this->is_lihat = true;
    }
}
