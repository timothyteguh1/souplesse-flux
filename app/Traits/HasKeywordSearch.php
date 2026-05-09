<?php

namespace App\Traits;

trait HasKeywordSearch
{
    /*
    * Search for a keyword in the given columns using full-text search.
    *
    * @param  \Illuminate\Database\Eloquent\Builder  $query
    * @param  string  $keyword
    * @param  array  $columns
    * @return \Illuminate\Database\Eloquent\Builder
    */
    public function scopeKeywordSearch($query, $keyword, $columns = ['kode', 'nama'])
    {
        if (empty($keyword) || empty($columns)) {
            return $query;
        }

        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $keyword = trim($keyword);
        $keyword = str_replace($reservedSymbols, ' ', $keyword);
        $keywords = explode(' ', $keyword);

        foreach ($keywords as $kw) {
            if (empty($kw)) {
                continue;
            }
            $query->where(function ($query) use ($kw, $columns) {
                foreach ($columns as $column) {
                    $query->orWhere($column, 'LIKE', "%{$kw}%");
                }
            });
        }

        return $query;
    }
}
