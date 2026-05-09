<?php

namespace App\Traits\Livewire;

trait WithIndexReportForm
{
    public $keyword;
    public $lists;
    public $results;

    public function updatedKeyword()
    {
        $this->results = $this->lists->filter(function ($item) {
            $title = strtolower($item['title']);
            $description = strtolower($item['description']);
            $keyword = strtolower($this->keyword);

            return str_contains($title, $keyword) || str_contains($description, $keyword);
        });
    }
}
