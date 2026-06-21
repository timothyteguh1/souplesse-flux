<?php

namespace App\Services\Penjualan;

use App\Exceptions\GeneralException;
use App\Utilities\Constants\Const_Status;
use App\Models\Penjualan\PesananPenjualan;
use App\Models\Penjualan\PesananPenjualanDetail;
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class PesananPenjualanService
{
    public static function create(array $data = []): PesananPenjualan
    {
        if (!PesananPenjualan::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = PesananPenjualan::create($data);
        
        // detail
        foreach ($data['items'] as $item) {
            PesananPenjualanDetailService::create($obj, $item);
        }
        
        $obj->refresh();

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return $obj;
    }

    public static function update(PesananPenjualan $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);

        self::updateDetail($obj, $data['items']);
        
        $obj->refresh();

        // --- TRIGGER PUSH KE ACCURATE ---
        self::pushToAccurate($obj);

        return true;
    }

    public static function updateDetail(PesananPenjualan $obj, array $data = []): bool
    {
        $collects = collect([
            $data,
        ]);

        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PesananPenjualanDetailService::destroy($dataLama);
        }

        // insert data baru
        foreach ($data as $item) {
            $item['pesanan_penjualan_id'] = $obj->id;

            $pesananPenjualanDetail = PesananPenjualanDetail::find($item['id']);
            if ($pesananPenjualanDetail) {
                PesananPenjualanDetailService::update($obj, $item);
            } else {
                PesananPenjualanDetailService::create($obj, $item);
            }
        }

        return true;
    }

    // ========================================================
    // LOGIKA PUSH DATA PESANAN PENJUALAN (SO) KE ACCURATE
    // ========================================================
  // ========================================================
    // LOGIKA PUSH DATA PESANAN PENJUALAN (SO) KE ACCURATE
    // ========================================================
    protected static function pushToAccurate(PesananPenjualan $so): void
    {
        try {
            Log::info("=== MULAI PUSH SO '{$so->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first(); 
            if (!$perusahaan) return; 

            // Pastikan relasi pelanggan, karyawan, dan detail produk diload
            $so->loadMissing(['details.produk', 'customer', 'karyawan']);

            $accurateService = app(AccurateService::class);

            // Mapping header
            $payload = [
                'number'       => $so->kode,
                // Format tanggal anti-error
                'transDate'    => $so->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $so->tanggal))->format('d/m/Y') : date('d/m/Y'),
                'description'  => $so->keterangan ?? '',
                'cashDiscount' => $so->diskon_rupiah,
                'inclusiveTax' => $so->is_include_ppn ? 'true' : 'false',
                'tax1Name'     => $so->is_pkp ? 'PPN' : '',
            ];

            // Mapping Customer & Karyawan
            if ($so->customer) {
                $payload['customerNo'] = $so->customer->kode;
            }
            if ($so->karyawan) {
                $payload['employeeNo'] = $so->karyawan->kode;
            }

            if ($so->accurate_id) {
                $payload['id'] = $so->accurate_id;
            }

            // --- PERBAIKAN: MAPPING BARANG JURUS "JAVA BINDING" ---
            // Mengubah format array PHP menjadi format list ber-indeks milik Java (Spring)
            $index = 0;
            foreach ($so->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemNo"]    = $detail->produk->kode; // SKU Produk
                    $payload["detailItem[$index].unitPrice"] = $detail->harga;        // Harga
                    $payload["detailItem[$index].quantity"]  = $detail->qty;          // Qty
                    
                    // (Opsional) Jika item ada diskonnya, aktifkan baris ini:
                    // $payload["detailItem[$index].itemDiscountPercent"] = $detail->diskon ?? 0;
                    
                    $index++;
                }
            }

            // Tembak API save SO
            $response = $accurateService->apiPost($perusahaan, '/sales-order/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH SO: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk SO:", $response);

            // Tangkap ID yang diberikan Accurate jika sukses
            if (isset($response['s']) && $response['s'] === true && !$so->accurate_id) {
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                if ($accurateId) {
                    $so->updateQuietly(['accurate_id' => $accurateId]); 
                    Log::info("SUKSES! SO masuk dengan Accurate ID: " . $accurateId);
                }
            } else if (!isset($response['s']) || $response['s'] !== true) {
                 Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push SO ke Accurate: ' . $e->getMessage());
        }
    }
    // --- (Fungsi Update Status lainnya tetap sama) ---
    public static function updateStatusMenungguPersetujuan(PesananPenjualan $obj)
    {
        if ($obj->fakturPenjualanDetails()->count() > 0) {
            throw new GeneralException('Pesanan Penjualan tidak bisa diubah statusnya karena sudah ada Faktur Penjualan.');
        }

        $obj->status = Const_Status::PESANAN_PENJUALAN_MENUNGGU_PERSETUJUAN;
        $obj->save();
    }

    public static function updateStatusTolak(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_DITOLAK;
        $obj->save();
    }

    public static function updateStatusTerima(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
        $obj->save();
    }

    public static function updateStatusTutup(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_DITUTUP;
        $obj->save();
    }

    public static function updateStatusSelesai(PesananPenjualan $obj)
    {
        $obj->status = Const_Status::PESANAN_PENJUALAN_SELESAI;
        $obj->save();
    }

    public static function updateStatus(PesananPenjualan $obj): bool
    {
        if ($obj->is_terpenuhi) {
            $obj->status = Const_Status::PESANAN_PENJUALAN_SELESAI;
        } else {
            $obj->status = Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI;
        }

        if ($obj->isDirty('status')) {
            $obj->save();
        }

        return true;
    }

    public static function destroy(PesananPenjualan $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        foreach ($obj->details as $detail) {
            PesananPenjualanDetailService::destroy($detail);
        }

        return $obj->delete();
    }
}