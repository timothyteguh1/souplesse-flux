<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Master\Jabatan;
use App\Models\Master\Karyawan;
use Illuminate\Validation\Rule;
use App\Exceptions\GeneralException;
use Maatwebsite\Excel\Concerns\ToArray;
use App\Services\Master\KaryawanService;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class KaryawanImport implements ToArray, WithHeadingRow, WithCalculatedFormulas, WithValidation
{
    use Importable;

    public $count = 0;

    public function __construct(protected $cabang_id) {}

    public function rules(): array
    {
        return [
            'kode' => [],
            'nama' => [
                'string', 'required',
                Rule::unique(Karyawan::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'jabatan' => ['required'],
            'user' => ['required'],
            'no_ktp' => ['required'],
            'tanggal_masuk' => ['required'],
            'telp' => [],
            'alamat' => [],
            'kota' => [],
            'provinsi' => [],
        ];
    }

    public function array(array $array)
    {
        if (count($array) == 0) {
            throw new GeneralException('Item tidak ditemukan.');
        }

        foreach ($array as $row) {
            $row['cabang_id'] = $this->cabang_id;
            if (gettype($row['tanggal_masuk']) != "string") {
                $row['tanggal_masuk'] = Carbon::instance(Date::excelToDateTimeObject($row['tanggal_masuk']));
            } else {
                $row['tanggal_masuk'] = Carbon::createFromFormat('Y-m-d', $row['tanggal_masuk']);
            }

            $jabatan = Jabatan::where('nama', $row['jabatan'])->first();
            if (!$jabatan && $row['jabatan']) {
                $jabatan = Jabatan::create([
                    'cabang_id' => $this->cabang_id,
                    'nama' => $row['jabatan'],
                ]);
            }
            $row['jabatan_id'] = $jabatan->id;

            $jabatan = Jabatan::where('nama', $row['jabatan'])->first();
            if (!$jabatan) {
                throw new GeneralException('Gagal Import Data. Jabatan ' . $row['jabatan'] . ' tidak ditemukan.');
            }
            $row['jabatan_id'] = $jabatan->id;

            $user = User::where('name', $row['user'])->first();
            if (!$user) {
                throw new GeneralException('Gagal Import Data. User ' . $row['user'] . ' tidak ditemukan.');
            }
            $row['user_id'] = $user->id;
            KaryawanService::create($row);

            $this->count++;
        }
    }
}
