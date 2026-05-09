<?php

namespace App\Imports;

use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use Illuminate\Validation\Rule;
use App\Exceptions\GeneralException;
use App\Models\Master\JenisProduk;
use App\Models\Master\KategoriProduk;
use App\Models\Master\ModelProduk;
use App\Services\Master\ProdukService;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class ProdukImport implements ToArray, WithHeadingRow, WithCalculatedFormulas, WithValidation
{
    use Importable;

    public $count = 0;

    public function __construct(protected $cabang_id) {}

    public function rules(): array
    {
        return [
            'kode' => [
                'string',
                'nullable',
                Rule::unique(Produk::getTableName(), 'kode')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'nama' => [
                'string',
                'required',
                Rule::unique(Produk::getTableName(), 'nama')
                    ->where('cabang_id', $this->cabang_id),
            ],
            'jenis' => ['required'],
            'kategori' => ['required'],
            'model' => ['required'],
            'harga_beli' => ['nullable', 'numeric', 'min:0'],
            'harga_jual' => ['nullable', 'numeric', 'min:0'],
            'minimal_order' => ['nullable', 'numeric', 'min:0'],
            'stok_minimum' => ['nullable', 'numeric', 'min:0'],
            'deskripsi' => [],
            'keterangan' => [],
        ];
    }

    public function array(array $array)
    {
        if (count($array) == 0) {
            throw new GeneralException('Item tidak ditemukan.');
        }
        foreach ($array as $row) {
            //kategori
            $row['kategori'] = trim($row['kategori']);
            $kategoriProduk = KategoriProduk::where('nama', $row['kategori'])->first();
            if (!$kategoriProduk && $row['kategori']) {
                $kategoriProduk = KategoriProduk::create([
                    'cabang_id' => $this->cabang_id,
                    'nama' => $row['kategori'],
                ]);
            }
            $row['kategori_produk_id'] = $kategoriProduk->id;

            //jenis
            $row['jenis'] = trim($row['jenis']);
            $jenisProduk = JenisProduk::where('nama', $row['jenis'])->first();
            if (!$jenisProduk && $row['jenis']) {
                $jenisProduk = JenisProduk::create([
                    'cabang_id' => $this->cabang_id,
                    'nama' => $row['jenis'],
                ]);
            }
            $row['jenis_produk_id'] = $jenisProduk->id;

            //model
            $row['model'] = trim($row['model']);
            $modelProduk = ModelProduk::where('nama', $row['model'])->first();
            if (!$modelProduk && $row['model']) {
                $modelProduk = ModelProduk::create([
                    'cabang_id' => $this->cabang_id,
                    'nama' => $row['model'],
                ]);
            }
            $row['model_produk_id'] = $modelProduk->id;

            //satuan dasar
            $satuanPcs = Satuan::where('nama', 'PCS')->first();
            $row['satuan_id'] = $satuanPcs->id;
            $row['keterangan'] = $row['internal_note'];
            $row['cabang_id'] = $this->cabang_id;

            ProdukService::create($row);

            $this->count++;
        }
    }
}
