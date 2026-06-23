<?php

namespace App\Services\Pembelian;

use App\Exceptions\GeneralException;
use App\Models\Pembelian\PesananPembelian;
use App\Models\Pembelian\PesananPembelianDetail;
use App\Utilities\Constants\Const_Status;
use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Illuminate\Support\Facades\Log;

class PesananPembelianService
{
    public static function create(array $data = []): PesananPembelian
    {
        if (!PesananPembelian::canPermissionCreate()) {
            throw new GeneralException('Tidak dapat menambah item');
        }

        $obj = PesananPembelian::create($data);
        foreach ($data['items'] as $item) {
            PesananPembelianDetailService::create($obj, $item);
        }

        return $obj;
    }

    public static function update(PesananPembelian $obj, array $data = []): bool
    {
        if (!$obj->canEdit()) {
            throw new GeneralException('Tidak dapat mengubah item ini');
        }

        $obj->update($data);
        self::updateDetail($obj, $data['items']);
        $obj->refresh();

        return true;
    }

    public static function updateDetail(PesananPembelian $obj, array $data = []): bool
    {
        $collects = collect([$data]);
        $updatedIds = $collects->pluck('*.id')->flatten()->whereNotNull();
        $dataLamas = $obj->details()->whereNotIn('id', $updatedIds)->get();

        foreach ($dataLamas as $dataLama) {
            PesananPembelianDetailService::destroy($dataLama);
        }

        foreach ($data as $item) {
            $item['pesanan_pembelian_id'] = $obj->id;
            $pesananPembelianDetail = PesananPembelianDetail::find($item['id']);
            if ($pesananPembelianDetail) {
                PesananPembelianDetailService::update($obj, $item);
            } else {
                PesananPembelianDetailService::create($obj, $item);
            }
        }

        return true;
    }

    public static function updateStatusTolak(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_DITOLAK;
        $obj->save();
    }

    public static function updateStatusTerima(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA;
        $obj->save();
        
        self::pushToAccurate($obj);
    }

    public static function updateStatusDalamPengiriman(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_DALAM_PENGIRIMAN;
        $obj->save();
    }

    public static function updateStatusSelesai(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_SELESAI;
        $obj->save();
        foreach ($obj->details as $detail) {
            PesananPembelianDetailService::selesai($obj, $detail);
        }

        self::pushReceiveItemToAccurate($obj);
    }

    public static function updateStatusTutup(PesananPembelian $obj)
    {
        $obj->status = Const_Status::PESANAN_PEMBELIAN_DITUTUP;
        $obj->save();
    }

    public static function updateStatus(PesananPembelian $obj): bool
    {
        if ($obj->is_terpenuhi) {
            $obj->status = Const_Status::PESANAN_PEMBELIAN_SELESAI;
        } else {
            $obj->status = Const_Status::PESANAN_PEMBELIAN_BELUM_DITERIMA;
        }

        if ($obj->isDirty('status')) {
            $obj->save();
        }

        return true;
    }

    public static function destroy(PesananPembelian $obj): bool
    {
        if (!$obj->canDelete()) {
            throw new GeneralException('Tidak dapat menghapus item ini');
        }

        foreach ($obj->details as $detail) {
            PesananPembelianDetailService::destroy($detail);
        }

        return $obj->delete();
    }

    // ========================================================
    // LOGIKA 1: PUSH PESANAN PEMBELIAN (PURCHASE ORDER)
    // ========================================================
    protected static function pushToAccurate(PesananPembelian $po): void
    {
        try {
            Log::info("=== MULAI PUSH PO PEMBELIAN '{$po->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first();
            if (!$perusahaan) return;

            $po->loadMissing(['details.produk', 'supplier']);
            $accurateService = app(AccurateService::class);

            $alamatVendor = 'Alamat Belum Diatur';
            if ($po->supplier) {
                if (!empty($po->supplier->jalan)) {
                    $alamatVendor = $po->supplier->jalan;
                } elseif (!empty($po->supplier->alamat)) {
                    $alamatVendor = $po->supplier->alamat;
                }
            }

            $payload = [
                'number'       => $po->kode,
                'transDate'    => $po->tanggal ? \Carbon\Carbon::parse(str_replace('/', '-', $po->tanggal))->format('d/m/Y') : date('d/m/Y'),
                'description'  => $po->keterangan ?? '',
                'cashDiscount' => $po->diskon_rupiah ?? 0,
                'inclusiveTax' => $po->is_include_ppn ? 'true' : 'false',
                'tax1Name'     => $po->is_pkp ? 'PPN' : '',
                'toAddress'    => $alamatVendor, 
            ];

            if ($po->supplier) {
                $payload['vendorNo'] = $po->supplier->kode;
            }

            if ($po->accurate_id) {
                $payload['id'] = $po->accurate_id;
            }

            $index = 0;
            foreach ($po->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemNo"]    = $detail->produk->kode; 
                    $payload["detailItem[$index].unitPrice"] = $detail->harga_satuan; 
                    $payload["detailItem[$index].quantity"]  = $detail->jumlah;       
                    $index++;
                }
            }

            $response = $accurateService->apiPost($perusahaan, '/purchase-order/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH PO: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            if (isset($response['s']) && $response['s'] === true && !$po->accurate_id) {
                $accurateId = $response['r']['id'] ?? ($response['d']['id'] ?? null);
                if ($accurateId) {
                    $po->updateQuietly(['accurate_id' => $accurateId]);
                    Log::info("SUKSES! PO Pembelian masuk dengan Accurate ID: " . $accurateId);
                }
            } else if (!isset($response['s']) || $response['s'] !== true) {
                Log::error('DITOLAK ACCURATE! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push PO Pembelian ke Accurate: ' . $e->getMessage());
        }
    }

    // ========================================================
    // LOGIKA 2: PUSH PENERIMAAN BARANG (RECEIVE ITEM)
    // ========================================================
    protected static function pushReceiveItemToAccurate(PesananPembelian $po): void
    {
        try {
            Log::info("=== MULAI PUSH PENERIMAAN BARANG DARI PO '{$po->kode}' KE ACCURATE ===");

            $perusahaan = Perusahaan::whereNotNull('accurate_host')->first();
            if (!$perusahaan) return;

            $po->loadMissing(['details.produk', 'supplier']);
            $accurateService = app(AccurateService::class);

            $payload = [
                'transDate'     => date('d/m/Y'),
                'description'   => 'Penerimaan otomatis dari PO lokal: ' . $po->kode,
                
                // --- SOLUSI FINAL ---
                // Parameter 'receiveNumber' WAJIB diisi (Nomor Surat Jalan/DO Vendor)
                // Kita generate otomatis dari nomor PO
                'receiveNumber' => 'DO-' . str_replace('/', '', $po->kode), 
            ];

            if ($po->supplier) {
                $payload['vendorNo'] = $po->supplier->kode;
            }

            $index = 0;
            foreach ($po->details as $detail) {
                if ($detail->produk) {
                    $payload["detailItem[$index].itemNo"]   = $detail->produk->kode;
                    $payload["detailItem[$index].quantity"] = $detail->jumlah;
                    
                    // --- TAMBAHAN WAJIB DARI DOKUMENTASI ---
                    $payload["detailItem[$index].unitPrice"]= $detail->harga_satuan; 
                    
                    $payload["detailItem[$index].purchaseOrderNumber"] = $po->kode; 
                    
                    $index++;
                }
            }

            $response = $accurateService->apiPost($perusahaan, '/receive-item/save.do', $payload);

            if ($response === null) {
                Log::error("BATAL PUSH PENERIMAAN BARANG: Fungsi apiPost mengembalikan nilai NULL.");
                return;
            }

            Log::info("Jawaban Server Accurate untuk Penerimaan Barang:", $response);

            if (isset($response['s']) && $response['s'] === true) {
                Log::info("SUKSES! Penerimaan Barang (Stok Masuk) berhasil dibuat di Accurate.");
            } else {
                Log::error('DITOLAK ACCURATE (Penerimaan Barang)! Alasan:', $response);
            }

        } catch (\Exception $e) {
            Log::error('GAGAL FATAL saat Push Penerimaan Barang ke Accurate: ' . $e->getMessage());
        }
    }
}