<?php

namespace App\Imports;

use App\Exceptions\GeneralException;
use App\Models\Master\Supplier;
use App\Services\Master\SupplierService;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SupplierImport implements ToArray, WithHeadingRow, WithCalculatedFormulas, WithValidation
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
                Rule::unique(Supplier::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'telp' => [],
            'handphone' => [],
            'email' => [],
            'alamat' => [],
            'kota' => [],

            'pkp' => [],

            'jatuh_tempo' => [],
            'nama_bank' => [],
            'nomor_rekening' => [],
            'atas_nama' => [],
            'npwp' => [],

            'payment_info' => [],
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

            $row['jatuh_tempo'] = $row['jatuh_tempo'] ?: 0;
            $row['is_pkp'] = $row['pkp'] == 1 ? true : false;

            $row['rekening_bank'] = $row['nama_bank'];
            $row['rekening_nomor'] = $row['nomor_rekening'];
            $row['rekening_nama'] = $row['atas_nama'];
            $row['keterangan'] = $row['internal_note'];
            SupplierService::create($row);

            $this->count++;
        }
    }
}
