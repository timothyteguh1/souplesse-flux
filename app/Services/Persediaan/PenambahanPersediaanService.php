<?php

namespace App\Services\Persediaan;

use App\Exceptions\GeneralException;
use App\Models\Persediaan\PenambahanPersediaan;
use App\Models\Persediaan\PenambahanPersediaanDetail;

// --- TAMBAHAN NAMESPACE UNTUK INTEGRASI ACCURATE ---
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class PenambahanPersediaanService
{
    public static function create(array $data = []): PenambahanPersediaan
    {
        $obj = PenambahanPersediaan::create($data);
        foreach ($data['items'] as $item) {
            PenambahanPersediaanDetailService::create($obj, $item);
        }
        self::pushToAccurate($obj);
        return $obj;
    }

    public static function update(PenambahanPersediaan $obj, array $data = []): bool
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

    public static function updateDetail(PenambahanPersediaan $obj, array $data = []): bool
    {
        $collects = collect([$data]);
        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PenambahanPersediaanDetailService::destroy($dataLama);
        }

        foreach ($data as $item) {
            $penambahanPersediaanDetail = PenambahanPersediaanDetail::find($item['id']);
            if ($penambahanPersediaanDetail) {
                PenambahanPersediaanDetailService::update($penambahanPersediaanDetail, $item);
            } else {
                PenambahanPersediaanDetailService::create($obj, $item);
            }
        }
        return true;
    }

    public static function destroy(PenambahanPersediaan $obj): bool
    {
        if (! $obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }
        foreach ($obj->details as $detail) {
            PenambahanPersediaanDetailService::destroy($detail);
        }
        return $obj->delete();
    }

    // ========================================================
    // LOGIKA PUSH PENAMBAHAN PERSEDIAAN (EXACT MATCH UPSERT)
    // ========================================================
    protected static function pushToAccurate(PenambahanPersediaan $penambahan): void
    {
        try {
            Log::info("=== MULAI PUSH PENAMBAHAN PERSEDIAAN '{$penambahan->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first();
            if (!$perusahaan) return;

            $penambahan->loadMissing(['details.produk']);
            $accurateService = app(AccurateService::class);

            // 1. PENCARIAN TEPAT SASARAN MENGGUNAKAN FILTER NUMBER
            $searchUrl = '/item-adjustment/list.do?fields=id,number&filter.number.op=EQUAL&filter.number.val[0]=' . urlencode($penambahan->kode);
            $searchResponse = $accurateService->apiGet($perusahaan, $searchUrl);
            
            $existingId = null;

            // VALIDASI KETAT: Cek satu per satu hasil pencarian
            if (isset($searchResponse['s']) && $searchResponse['s'] === true && !empty($searchResponse['d'])) {
                foreach ($searchResponse['d'] as $row) {
                    if (isset($row['number']) && $row['number'] === $penambahan->kode) {
                        $existingId = $row['id'];
                        break; 
                    }
                }
            }

            if ($existingId) {
                Log::info("DEBUG: Dokumen '{$penambahan->kode}' SAMA PERSIS ditemukan dengan ID: {$existingId}. Mode: UPDATE.");
            } else {
                Log::info("DEBUG: Dokumen '{$penambahan->kode}' tidak ditemukan. Mode: CREATE.");
            }

            // 2. SIAPKAN PAYLOAD DASAR
            $tanggalFormat = $penambahan->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $penambahan->tanggal))->format('d/m/Y') : date('d/m/Y');

            $payload = [
                'number'      => $penambahan->kode,
                'transDate'   => $tanggalFormat,
                'description' => $penambahan->keterangan ?? 'Penambahan Persediaan otomatis dari sistem lokal',
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

            // 4. MASUKKAN DATA DETAIL BARU (ADJUSTMENT_IN)
            foreach ($penambahan->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemAdjustmentType"] = 'ADJUSTMENT_IN';
                    $payload["detailItem[$index].itemNo"]             = $detail->produk->kode;
                    $payload["detailItem[$index].quantity"]           = $detail->jumlah;
                    $payload["detailItem[$index].unitCost"]           = $detail->harga_satuan ?? 0;
                    $index++;
                }
            }

            Log::info("=== DEBUG PAYLOAD PENAMBAHAN PERSEDIAAN '{$penambahan->kode}' ===");
            Log::info(json_encode($payload, JSON_PRETTY_PRINT));

            // 5. EKSEKUSI PUSH
            $response = $accurateService->apiPost($perusahaan, '/item-adjustment/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            if (isset($response['s']) && $response['s'] === true) {
                Log::info("SUKSES! Penambahan Persediaan berhasil di-push ke Accurate.");
            } else {
                Log::error('DITOLAK ACCURATE (Penambahan Persediaan)! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Penambahan Persediaan: ' . $e->getMessage());
        }
    }
}