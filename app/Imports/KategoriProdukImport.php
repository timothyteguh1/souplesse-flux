<?php

namespace App\Imports;

use App\Exceptions\GeneralException;
use App\Models\Master\KategoriProduk;
use App\Services\Master\KategoriProdukService;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KategoriProdukImport implements ToArray, WithHeadingRow, WithCalculatedFormulas, WithValidation
{
    use Importable;

    public $count = 0;

    public function __construct(protected $cabang_id) {}

    public function rules(): array
    {
        return [
            'kode' => [],
            'nama' => [
                'string',
                'required',
            ],
            'internal_note' => [],
        ];
    }

    public function array(array $array)
    {
        if (count($array) == 0) {
            throw new GeneralException('Item tidak ditemukan.');
        }

        foreach ($array as $row) {
            $row['cabang_id'] = $this->cabang_id;
            $row['nama'] = trim($row['nama']);
            $row['keterangan'] = $row['internal_note'];


            if (!KategoriProduk::where('nama', $row['nama'])->exists()) {
                KategoriProdukService::create($row);
            }

            $this->count++;
        }
    }
}
