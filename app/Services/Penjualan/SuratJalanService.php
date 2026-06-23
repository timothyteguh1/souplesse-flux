<?php

namespace App\Services\Penjualan;

use App\Models\Penjualan\PesananPenjualan;
use App\Models\Penjualan\SuratJalan;
use App\Models\Penjualan\SuratJalanDetail;
use App\Utilities\Constants\Const_Status;
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class SuratJalanService
{
    public static function create(array $data = []): SuratJalan
    {
        $obj = SuratJalan::create($data);
        
        // detail
        foreach ($data['items'] as $item) {
            SuratJalanDetailService::create($obj, $item);
        }

        $pesananPenjualan = PesananPenjualan::find($obj->pesanan_penjualan_id);
        PesananPenjualanService::updateStatusSelesai($pesananPenjualan);

        $obj->refresh();

        // --- TRIGGER PUSH SJ KE ACCURATE ---
        self::pushToAccurate($obj);

        return $obj;
    }

    public static function update(SuratJalan $obj, array $data = []): bool
    {
        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        $obj->refresh();

        // --- TRIGGER PUSH SJ KE ACCURATE ---
        self::pushToAccurate($obj);

        return true;
    }

    public static function updateDetail(SuratJalan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            SuratJalanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $pesananPenjualanDetail = SuratJalanDetail::find($item['id']);
            if ($pesananPenjualanDetail) {
                SuratJalanDetailService::update($pesananPenjualanDetail, $item);
            } else {
                SuratJalanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    // ========================================================
    // LOGIKA PUSH SURAT JALAN (DELIVERY ORDER) KE ACCURATE
    // ========================================================
    protected static function pushToAccurate(SuratJalan $sj): void
    {
        try {
            Log::info("=== MULAI PUSH SURAT JALAN '{$sj->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            if (!$perusahaan) return; 

            // Load relasi ke Customer dan Detail Barang
            $sj->loadMissing(['details.produk', 'customer', 'pesananPenjualan']);

            $accurateService = app(AccurateService::class);

            // Mapping header SJ
            $payload = [
                'number'       => $sj->kode,
                // Mengubah format tanggal menggunakan Carbon anti-error
                'transDate'    => $sj->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $sj->tanggal))->format('d/m/Y') : date('d/m/Y'),
                'description'  => $sj->keterangan ?? '',
            ];

            // Mapping Customer Lokal -> Accurate
            if ($sj->customer) {
                $payload['customerNo'] = $sj->customer->kode;
            }

            // Jika SJ ini ditarik dari SO, beri tahu Accurate!
            // Supaya SO di Accurate juga ikut ter-update statusnya.
            if ($sj->pesananPenjualan && $sj->pesananPenjualan->accurate_id) {
                $payload['salesOrderId'] = $sj->pesananPenjualan->accurate_id;
            }

            // Jika SJ lokal ini hasil update
            if ($sj->accurate_id) {
                $payload['id'] = $sj->accurate_id;
            }

            // Mapping Detail Barang dengan Jurus "Java Binding"
            $index = 0;
            foreach ($sj->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemNo"]   = $detail->produk->kode; // SKU Produk
                    $payload["detailItem[$index].quantity"] = $detail->qty;          // Qty yang dikirim
                    
                    // Harga biasanya tidak ditampilkan di cetakan Surat Jalan, 
                    // tapi API DO Accurate tetap membutuhkan nilai default
                    $payload["detailItem[$index].unitPrice"] = 0; 
                    
                    $index++;
                }
            }

            // Tembak API save Delivery Order
            $response = $accurateService->apiPost($perusahaan, '/delivery-order/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH SJ: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk Surat Jalan:", $response);

            // Tangkap ID yang diberikan Accurate jika sukses
            if (isset($response['s']) && $response['s'] === true && !$sj->accurate_id) {
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                if ($accurateId) {
                    $sj->updateQuietly(['accurate_id' => $accurateId]); 
                    Log::info("SUKSES! Surat Jalan masuk dengan Accurate ID: " . $accurateId);
                }
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Surat Jalan ke Accurate: ' . $e->getMessage());
        }
    }

    public static function updateStatusBelumSelesai(SuratJalan $obj)
    {
        $obj->status = Const_Status::SURAT_JALAN_BELUM_SELESAI;
        $obj->save();
    }

    public static function updateStatusSelesai(SuratJalan $obj)
    {
        $obj->status = Const_Status::SURAT_JALAN_SELESAI;
        $obj->save();
    }

    public static function updateStatus(SuratJalan $obj): bool
    {
        if ($obj->is_terfaktur_semua) {
            $obj->status = Const_Status::SURAT_JALAN_SELESAI;
        } else {
            $obj->status = Const_Status::SURAT_JALAN_BELUM_SELESAI;
        }

        if ($obj->isDirty('status')) {
            $obj->save();
        }

        return true;
    }

    public static function destroy(SuratJalan $obj): bool
    {
        foreach ($obj->details as $detail) {
            SuratJalanDetailService::destroy($detail);
        }

        $pesananPenjualan = PesananPenjualan::find($obj->pesanan_penjualan_id);
        PesananPenjualanService::updateStatusBelumDikirim($pesananPenjualan);

        return $obj->delete();
    }
}