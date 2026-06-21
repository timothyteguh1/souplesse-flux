<?php

namespace App\Services\Master;

use App\Exceptions\GeneralException;
use App\Models\Master\Customer;
use App\Models\Master\CustomerDiskon;
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class CustomerService
{
    public static function create(array $data = []): Customer
    {
        if (! Customer::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = Customer::create($data);

        foreach ($data['items'] as $item) {
            CustomerDiskonService::create($obj, $item);
        }

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return $obj;
    }

    public static function update(Customer $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        self::updateDetail($obj, $data['items']);
        $obj->update($data);

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return true;
    }

    public static function updateDetail(Customer $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();

        $dataLamas = $obj->customerDiskons()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            CustomerDiskonService::destroy($dataLama);
        }
        // insert data baru
        foreach ($data as $item) {
            $customerDiskon = CustomerDiskon::find($item['id']);
            if ($customerDiskon) {
                CustomerDiskonService::update($customerDiskon, $item);
            } else {
                CustomerDiskonService::create($obj, $item);
            }
        }

        return true;
    }

    public static function destroy(Customer $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        return $obj->delete();
    }

    // ========================================================
    // LOGIKA PUSH DATA CUSTOMER KE ACCURATE
    // ========================================================
    protected static function pushToAccurate(Customer $customer): void
    {
        try {
            Log::info("=== MULAI PUSH CUSTOMER '{$customer->nama}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            
            if (!$perusahaan) {
                Log::error("BATAL PUSH: Data Perusahaan kosong atau belum terhubung ke Accurate.");
                return; 
            }

            $accurateService = app(AccurateService::class);

            // Mapping kolom tabel lokal dengan API Customer Accurate
         // Mapping kolom tabel lokal dengan API Customer Accurate
            $payload = [
                'name'        => $customer->nama,
                'customerNo'  => $customer->kode, 
                'workPhone'   => $customer->telp,
                'mobilePhone' => $customer->handphone,
                'email'       => $customer->email,
                
                // --- PERBAIKAN NAMA KOLOM ALAMAT ---
                'billStreet'  => $customer->alamat,
                'billCity'    => $customer->kota,
                
                'notes'       => $customer->keterangan,
            ];

            // Jika update (sudah punya accurate_id sebelumnya)
            if ($customer->accurate_id) {
                $payload['id'] = $customer->accurate_id;
            }

            $response = $accurateService->apiPost($perusahaan, '/customer/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban dari Server Accurate untuk Customer:", $response);

            // Cek sukses simpan dan baru pertama kali simpan
            if (isset($response['s']) && $response['s'] === true && !$customer->accurate_id) {
                
                // Ambil ID balasan dari server Accurate
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                
                if ($accurateId) {
                    Customer::where('id', $customer->id)->update([
                        'accurate_id' => $accurateId
                    ]);
                    Log::info("SUKSES! Customer berhasil masuk dengan Accurate ID: " . $accurateId);
                }
                
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Customer ke Accurate: ' . $e->getMessage());
        }
    }
}