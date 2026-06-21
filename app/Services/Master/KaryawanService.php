<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Karyawan;
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class KaryawanService
{
    public static function create(array $data = []): Karyawan
    {
        $data = self::validationNull($data);
        $obj = Karyawan::create($data);

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return $obj;
    }

    public static function update(Karyawan $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $data = self::validationNull($data);
        $obj->update($data);

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return true;
    }

    public static function destroy(Karyawan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }

    public static function validationNull(array $data = []): array
    {
        $data['komisi'] = $data['komisi'] ?: 0;
        return $data;
    }

    // ========================================================
    // LOGIKA PUSH DATA KARYAWAN (EMPLOYEE) KE ACCURATE
    // ========================================================
    protected static function pushToAccurate(Karyawan $karyawan): void
    {
        try {
            Log::info("=== MULAI PUSH KARYAWAN '{$karyawan->nama}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            
            if (!$perusahaan) {
                Log::error("BATAL PUSH: Data Perusahaan kosong atau belum terhubung ke Accurate.");
                return; 
            }

            $accurateService = app(AccurateService::class);

            // Mapping kolom tabel lokal dengan API Employee Accurate
            $payload = [
                'name'        => $karyawan->nama,
                'no'          => $karyawan->kode, // Accurate menggunakan parameter 'no' untuk ID Karyawan
                'workPhone'   => $karyawan->telp,
                'mobilePhone' => $karyawan->handphone,
                'email'       => $karyawan->email,
                
                // --- JURUS SAPU JAGAT ALAMAT ---
                'address'     => $karyawan->alamat,
                'street'      => $karyawan->alamat,
                'billStreet'  => $karyawan->alamat,
                
                'city'        => $karyawan->kota,
                'billCity'    => $karyawan->kota,
                
                'notes'       => $karyawan->keterangan,
            ];

            if ($karyawan->accurate_id) {
                $payload['id'] = $karyawan->accurate_id;
            }

            $response = $accurateService->apiPost($perusahaan, '/employee/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk Karyawan:", $response);

            if (isset($response['s']) && $response['s'] === true && !$karyawan->accurate_id) {
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                
                if ($accurateId) {
                    Karyawan::where('id', $karyawan->id)->update([
                        'accurate_id' => $accurateId
                    ]);
                    Log::info("SUKSES! Karyawan masuk dengan Accurate ID: " . $accurateId);
                }
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Karyawan ke Accurate: ' . $e->getMessage());
        }
    }
}