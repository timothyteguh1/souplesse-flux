<?php

namespace App\Imports;

use App\Models\Master\Customer;
use Illuminate\Validation\Rule;
use App\Exceptions\GeneralException;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Services\Master\CustomerService;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class CustomerImport implements ToArray, WithHeadingRow, WithCalculatedFormulas, WithValidation
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
                Rule::unique(Customer::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'telp' => [
                'nullable',
            ],
            'handphone' => [
                'nullable',
            ],
            'email' => [],
            'alamat' => [],
            'kota' => [],

            'blacklist' => [],
            'pkp' => [],

            'npwp_kode' => [],
            'npwp_nik' => [],
            'npwp_wajib_pajak' => [],
            'npwp_alamat' => [],
            'npwp_kota' => [],
            'npwp_kode_pos' => [],
            'npwp_provinsi' => [],

            'jatuh_tempo' => [],
            'limit_piutang' => ['min:0'],

            'nama_bank' => [],
            'nomor_rekening' => [],
            'atas_nama' => [],

            'metode_pembayaran_1' => [],
            'diskon_1' => [],

            'metode_pembayaran_2' => [],
            'diskon_2' => [],

            'metode_pembayaran_3' => [],
            'diskon_3' => [],

        ];
    }

    public function array(array $array)
    {
        if (count($array) == 0) {
            throw new GeneralException('Item tidak ditemukan.');
        }

        foreach ($array as $index => $row) {
            $row['cabang_id'] = $this->cabang_id;

            $row['jatuh_tempo'] = $row['jatuh_tempo'] ?: 0;
            $row['limit_piutang'] = $row['limit_piutang'] ?: 0;

            $row['is_blacklist'] = $row['blacklist'] == 1 ? true : false;
            $row['is_pkp'] = $row['pkp'] == 1 ? true : false;

            $row['rekening_bank'] = $row['nama_bank'];
            $row['rekening_nomor'] = $row['nomor_rekening'];
            $row['rekening_nama'] = $row['atas_nama'];

            if ($row['diskon_1']) {
                $row['items'][] = [
                    'metode_pembayaran' => $row['metode_pembayaran_1'],
                    'diskon' => $row['diskon_1'],
                ];
            }

            if ($row['diskon_2']) {
                $row['items'][] = [
                    'metode_pembayaran' => $row['metode_pembayaran_2'],
                    'diskon' => $row['diskon_2'],
                ];
            }

            if ($row['diskon_3']) {
                $row['items'][] = [
                    'metode_pembayaran' => $row['metode_pembayaran_3'],
                    'diskon' => $row['diskon_3'],
                ];
            }

            CustomerService::create($row);
            $this->count++;
        }
    }
}
