<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Produk;
use App\Models\Master\Perusahaan; 
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class ProdukService
{
    public static function create(array $data = []): Produk
    {
        if (!Produk::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $data = self::validationNumberNull($data);
        
        // 1. Simpan data ke database lokal terlebih dahulu
        $produk = Produk::create($data);

        // 2. Trigger push data ke Accurate Online
        self::pushToAccurate($produk);

        return $produk;
    }

    public static function update(Produk $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $data = self::validationNumberNull($data);
        $obj->update($data);

        // Push perubahan update data ke Accurate
        self::pushToAccurate($obj);

        return true;
    }

    public static function destroy(Produk $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }

    public static function validationNumberNull(array $data = []): array
    {
        $data['harga_beli'] = $data['harga_beli'] ?: 0;
        $data['harga_jual'] = $data['harga_jual'] ?: 0;
        $data['stok_minimum'] = $data['stok_minimum'] ?: 0;
        $data['minimal_order'] = $data['minimal_order'] ?: 0;

        return $data;
    }

    // ========================================================
    // LOGIKA PUSH DATA KE ACCURATE (DENGAN SUPER DEBUGGER)
    // ========================================================
    protected static function pushToAccurate(Produk $produk): void
    {
        try {
            Log::info("=== MULAI PUSH PRODUK '{$produk->nama}' KE ACCURATE ===");

            // FIX: Cari perusahaan yang BENAR-BENAR sudah terkoneksi Accurate
            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            
            if (!$perusahaan) {
                Log::error("BATAL PUSH: Data Perusahaan kosong atau belum terhubung ke Accurate.");
                return; 
            }

            Log::info("Perusahaan Ditemukan: Host = {$perusahaan->accurate_host}");

            $accurateService = app(AccurateService::class);

            // Mapping Satuan
            $satuan = \App\Models\Master\Satuan::find($produk->satuan_id);
            $namaSatuan = $satuan ? $satuan->nama : 'PCS';

            $payload = [
                'name'      => $produk->nama,
                'no'        => $produk->kode, 
                'itemType'  => 'INVENTORY', 
                'unitPrice' => $produk->harga_jual,
                'unit1Name' => $namaSatuan, 
            ];

            if ($produk->accurate_id) {
                $payload['id'] = $produk->accurate_id;
            }

            Log::info("Payload yang dikirim ke Accurate:", $payload);

            $response = $accurateService->apiPost($perusahaan, '/item/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH: Fungsi apiPost mengembalikan nilai NULL (Cek apakah Token Accurate kadaluarsa).");
                return;
            }

            Log::info("Jawaban dari Server Accurate:", $response);

            if (isset($response['s']) && $response['s'] === true && !$produk->accurate_id) {
                Produk::where('id', $produk->id)->update([
                    'accurate_id' => $response['d']['id']
                ]);
                Log::info("SUKSES! Produk baru berhasil masuk dengan Accurate ID: " . $response['d']['id']);
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push ke Accurate: ' . $e->getMessage());
        }
    }
}