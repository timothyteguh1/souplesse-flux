<?php

namespace App\Imports;

use App\Exceptions\GeneralException;
use App\Models\Master\Satuan;
use App\Services\Master\SatuanService;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SatuanImport implements ToArray, WithHeadingRow, WithCalculatedFormulas, WithValidation
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

            $isExist = Satuan::where('nama', $row['nama'])->first();
            if (!$isExist) {
                SatuanService::create($row);
            }

            $this->count++;
        }
    }
}
