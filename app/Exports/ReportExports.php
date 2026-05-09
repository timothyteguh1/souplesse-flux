<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportExports implements FromView, ShouldAutoSize
{
    protected $view;
    protected $data;
    protected $params;
    protected $file_type;

    public function __construct($view, $data, $file_type)
    {
        $this->view = $view;
        $this->data = $data;
        $this->file_type = $file_type;
    }

    public function view(): View
    {
        return view($this->view, array_merge($this->data, ['file_type' => $this->file_type]));
    }
}
