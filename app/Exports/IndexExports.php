<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class IndexExports implements FromView, ShouldAutoSize
{
    protected $view;
    protected $data;
    protected $params;
    protected $file_type;

    public function __construct($view, $data, $params, $file_type)
    {
        $this->view = $view;
        $this->data = $data;
        $this->params = $params;
        $this->file_type = $file_type;
    }

    public function view(): View
    {
        return view($this->view, [
            'data' => $this->data,
            'params' => $this->params,
            'file_type' => $this->file_type,
        ]);
    }
}
