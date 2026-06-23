<?php

namespace App\Services\Persediaan;

use App\Exceptions\GeneralException;
use App\Models\Persediaan\PenguranganPersediaan;
use App\Models\Persediaan\PenguranganPersediaanDetail;

// --- TAMBAHAN NAMESPACE UNTUK INTEGRASI ACCURATE ---
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class PenguranganPersediaanService
{
    public static function create(array $data = []): PenguranganPersediaan
    {
        $obj = PenguranganPersediaan::create($data);
        foreach ($data['items'] as $item) {
            PenguranganPersediaanDetailService::create($obj, $item);
        }
        self::pushToAccurate($obj);
        return $obj;
    }

    public static function update(PenguranganPersediaan $obj, array $data = []): bool
    {
        if (! $obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }
        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        $obj->refresh();
        self::pushToAccurate($obj);
        return true;
    }

    public static function updateDetail(PenguranganPersediaan $obj, array $data = []): bool
    {
        $collects = collect([$data]);
        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PenguranganPersediaanDetailService::destroy($dataLama);
        }

        foreach ($data as $item) {
            $penguranganPersediaanDetail = PenguranganPersediaanDetail::find($item['id']);
            if ($penguranganPersediaanDetail) {
                PenguranganPersediaanDetailService::update($penguranganPersediaanDetail, $item);
            } else {
                PenguranganPersediaanDetailService::create($obj, $item);
            }
        }
        return true;
    }

    public static function destroy(PenguranganPersediaan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }
        foreach ($obj->details as $detail) {
            PenguranganPersediaanDetailService::destroy($detail);
        }
        return $obj->delete();
    }

    // ========================================================
    // LOGIKA PUSH PENGURANGAN PERSEDIAAN (EXACT MATCH UPSERT)
    // ========================================================
    protected static function pushToAccurate(PenguranganPersediaan $pengurangan): void
    {
        try {
            Log::info("=== MULAI PUSH PENGURANGAN PERSEDIAAN '{$pengurangan->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first();
            if (!$perusahaan) return;

            $pengurangan->loadMissing(['details.produk']);
            $accurateService = app(AccurateService::class);

            // 1. PENCARIAN TEPAT SASARAN MENGGUNAKAN FILTER NUMBER
            $searchUrl = '/item-adjustment/list.do?fields=id,number&filter.number.op=EQUAL&filter.number.val[0]=' . urlencode($pengurangan->kode);
            $searchResponse = $accurateService->apiGet($perusahaan, $searchUrl);
            
            $existingId = null;

            // VALIDASI KETAT: Cek satu per satu hasil pencarian
            if (isset($searchResponse['s']) && $searchResponse['s'] === true && !empty($searchResponse['d'])) {
                foreach ($searchResponse['d'] as $row) {
                    if (isset($row['number']) && $row['number'] === $pengurangan->kode) {
                        $existingId = $row['id'];
                        break; 
                    }
                }
            }

            if ($existingId) {
                Log::info("DEBUG: Dokumen '{$pengurangan->kode}' SAMA PERSIS ditemukan dengan ID: {$existingId}. Mode: UPDATE.");
            } else {
                Log::info("DEBUG: Dokumen '{$pengurangan->kode}' tidak ditemukan. Mode: CREATE.");
            }

            // 2. SIAPKAN PAYLOAD DASAR
            $tanggalFormat = $pengurangan->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $pengurangan->tanggal))->format('d/m/Y') : date('d/m/Y');

            $payload = [
                'number'      => $pengurangan->kode,
                'transDate'   => $tanggalFormat,
                'description' => $pengurangan->keterangan ?? 'Pengurangan Persediaan otomatis dari sistem lokal',
            ];

            $index = 0;

            // 3. SAPU BERSIH (HANYA JIKA DOKUMEN BENAR-BENAR ADA)
            if ($existingId) {
                $payload['id'] = $existingId;
                
                $detailUrl = '/item-adjustment/detail.do?id=' . $existingId;
                $detailResponse = $accurateService->apiGet($perusahaan, $detailUrl);
                
                if (isset($detailResponse['d']['detailItem'])) {
                    foreach ($detailResponse['d']['detailItem'] as $oldDetail) {
                        $payload["detailItem[$index].id"] = $oldDetail['id'];
                        $payload["detailItem[$index]._status"] = 'delete';
                        $index++;
                    }
                    Log::info("DEBUG: Memasukkan perintah HAPUS untuk " . count($detailResponse['d']['detailItem']) . " baris detail lama.");
                }
            }

            // 4. MASUKKAN DATA DETAIL BARU (ADJUSTMENT_OUT)
            foreach ($pengurangan->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemAdjustmentType"] = 'ADJUSTMENT_OUT';
                    $payload["detailItem[$index].itemNo"]             = $detail->produk->kode;
                    $payload["detailItem[$index].quantity"]           = abs($detail->jumlah);
                    // Tidak ada unitCost untuk Pengurangan
                    $index++;
                }
            }

            Log::info("=== DEBUG PAYLOAD PENGURANGAN PERSEDIAAN '{$pengurangan->kode}' ===");
            Log::info(json_encode($payload, JSON_PRETTY_PRINT));

            // 5. EKSEKUSI PUSH
            $response = $accurateService->apiPost($perusahaan, '/item-adjustment/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            if (isset($response['s']) && $response['s'] === true) {
                Log::info("SUKSES! Pengurangan Persediaan berhasil di-push ke Accurate.");
            } else {
                Log::error('DITOLAK ACCURATE (Pengurangan Persediaan)! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Pengurangan Persediaan: ' . $e->getMessage());
        }
    }
}