<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Supplier;
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class SupplierService
{
    public static function create(array $data = []): Supplier
    {
        if (! Supplier::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = Supplier::create($data);

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return $obj;
    }

    public static function update(Supplier $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return true;
    }

    public static function destroy(Supplier $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }

    // ========================================================
    // LOGIKA PUSH DATA SUPPLIER (VENDOR) KE ACCURATE
    // ========================================================
    protected static function pushToAccurate(Supplier $supplier): void
    {
        try {
            Log::info("=== MULAI PUSH SUPPLIER '{$supplier->nama}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            
            if (!$perusahaan) {
                Log::error("BATAL PUSH: Data Perusahaan kosong atau belum terhubung ke Accurate.");
                return; 
            }

            $accurateService = app(AccurateService::class);

            // Mapping kolom tabel lokal dengan API Vendor Accurate
            $payload = [
                'name'        => $supplier->nama,
                'vendorNo'    => $supplier->kode, 
                'workPhone'   => $supplier->telp,
                'mobilePhone' => $supplier->handphone,
                'email'       => $supplier->email,
                'notes'       => $supplier->keterangan,
                
                // --- JURUS SAPU JAGAT ALAMAT ---
                // Mengirim semua variasi nama karena API Vendor Accurate sering inkonsisten
                'address'     => $supplier->alamat,
                'street'      => $supplier->alamat,
                'billStreet'  => $supplier->alamat,
                
                'city'        => $supplier->kota,
                'billCity'    => $supplier->kota,
            ];

            if ($supplier->accurate_id) {
                $payload['id'] = $supplier->accurate_id;
            }

            $response = $accurateService->apiPost($perusahaan, '/vendor/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk Supplier:", $response);

            if (isset($response['s']) && $response['s'] === true && !$supplier->accurate_id) {
                
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                
                if ($accurateId) {
                    Supplier::where('id', $supplier->id)->update([
                        'accurate_id' => $accurateId
                    ]);
                    Log::info("SUKSES! Supplier masuk dengan Accurate ID: " . $accurateId);
                }
                
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Supplier ke Accurate: ' . $e->getMessage());
        }
    }
}