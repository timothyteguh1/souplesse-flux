<?php

namespace App\Services\System;

use App\Models\Master\Produk;
use App\Models\System\MutasiStok;
use App\Models\Master\ProdukSatuan;
use App\Exceptions\GeneralException;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\Functions\InventoryFunction;

// class MutasiStokService
// {
// public static function increase(
//     $tanggal,
//     $cabang_id,
//     $reference_type,
//     $reference_id,
//     $header_type,
//     $header_id,
//     $jenis_transaksi,
//     $gudang_id,
//     $produk_id,
//     $satuan_transaksi_id,
//     $expired_date,
//     $no_batch,
//     $jumlah_transaksi,
//     $harga_transaksi,
//     $keterangan,
// ): ?MutasiStok {
//     $produk = Produk::findOrFail($produk_id);

//     // jika produk tidak memiliki stok, tidak perlu di proses mutasi stok
//     if ($produk->is_stok == 0) {
//         return null;
//     }

//     if ($jumlah_transaksi <= 0) {
//         throw new GeneralException('Jumlah mutasi stok harus lebih besar dari 0.');
//     }
//     if (!in_array($satuan_transaksi_id, $produk->satuans->pluck('id')->all())) {
//         throw new GeneralException('Satuan mutasi stok tidak terdaftar disatuan produk.');
//     }

//     $tanggal = _datetime_carbon_db($tanggal);

//     // dalam satuan terkecil
//     $produkSatuan = ProdukSatuan::query()
//         ->where('produk_id', $produk_id)
//         ->where('satuan_id', $satuan_transaksi_id)
//         ->first();

//     if (!$produkSatuan) {
//         throw new GeneralException('Satuan mutasi stok tidak terdaftar disatuan produk.');
//     }

//     $satuan_id = $produk->produkSatuan->satuan_id;
//     $konversi = $produkSatuan->konversi;
//     $jumlah = $konversi * $jumlah_transaksi;
//     $harga = $konversi == 0 ? 0 : _round($harga_transaksi / $konversi);

//     $data = [
//         'tanggal' => $tanggal,
//         'cabang_id' => $cabang_id,
//         'reference_type' => $reference_type,
//         'reference_id' => $reference_id,
//         'header_type' => $header_type,
//         'header_id' => $header_id,
//         'jenis_transaksi' => $jenis_transaksi,
//         'gudang_id' => $gudang_id,
//         'produk_id' => $produk_id,
//         'satuan_id' => $satuan_id,
//         'satuan_transaksi_id' => $satuan_transaksi_id,
//         'expired_date' => $expired_date,
//         'no_batch' => $no_batch,
//         'jumlah' => $jumlah,
//         'jumlah_transaksi' => $jumlah_transaksi,
//         'harga' => $harga,
//         'harga_transaksi' => $harga_transaksi,
//         'keterangan' => $keterangan,
//     ];

//     return MutasiStok::create($data);
// }

// public static function decrease(
//     $tanggal,
//     $cabang_id,
//     $reference_type,
//     $reference_id,
//     $header_type,
//     $header_id,
//     $jenis_transaksi,
//     $gudang_id,
//     $produk_id,
//     $satuan_transaksi_id,
//     $expired_date,
//     $no_batch,
//     $jumlah_transaksi,
//     $keterangan,
//     $harga_transaksi = null,
// ): ?MutasiStok {
//     $produk = Produk::findOrFail($produk_id);

//     // jika produk tidak memiliki stok, tidak perlu di proses mutasi stok
//     if ($produk->is_stok == 0) {
//         return null;
//     }

//     if ($jumlah_transaksi >= 0) {
//         throw new GeneralException('Jumlah mutasi stok harus lebih kecil dari 0.');
//     }

//     if (!in_array($satuan_transaksi_id, $produk->satuans->pluck('id')->all())) {
//         throw new GeneralException('Satuan mutasi stok tidak terdaftar disatuan produk.');
//     }

//     $tanggal = _datetime_carbon_db($tanggal);

//     // dalam satuan terkecil
//     $produkSatuan = ProdukSatuan::query()
//         ->where('produk_id', $produk_id)
//         ->where('satuan_id', $satuan_transaksi_id)
//         ->first();

//     if (!$produkSatuan) {
//         throw new GeneralException('Satuan mutasi stok tidak terdaftar disatuan produk.');
//     }

//     $satuan_id = $produk->produkSatuan->satuan_id;
//     $konversi = $produkSatuan->konversi;
//     $jumlah = $konversi * $jumlah_transaksi;
//     if ($harga_transaksi == null) {
//         $harga = InventoryFunction::getHpp($cabang_id, $produk_id, $tanggal);
//         $harga_transaksi = _round($harga * $konversi);
//     } else {
//         $harga = $konversi == 0 ? 0 : _round($harga_transaksi / $konversi);
//     }

//     $data = [
//         'tanggal' => $tanggal,
//         'cabang_id' => $cabang_id,
//         'reference_type' => $reference_type,
//         'reference_id' => $reference_id,
//         'header_type' => $header_type,
//         'header_id' => $header_id,
//         'jenis_transaksi' => $jenis_transaksi,
//         'gudang_id' => $gudang_id,
//         'produk_id' => $produk_id,
//         'satuan_id' => $satuan_id,
//         'satuan_transaksi_id' => $satuan_transaksi_id,
//         'expired_date' => $expired_date,
//         'no_batch' => $no_batch,
//         'jumlah' => $jumlah,
//         'jumlah_transaksi' => $jumlah_transaksi,
//         'harga' => $harga,
//         'harga_transaksi' => $harga_transaksi,
//         'keterangan' => $keterangan,
//     ];

//     // saldo produk pada saat transaksi
//     $saldoProdukTransaksi = InventoryFunction::getStok($cabang_id, $produk_id, $gudang_id, $tanggal, expired_date: $expired_date, no_batch: $no_batch);

//     if ($saldoProdukTransaksi + $jumlah < 0) {
//         throw new GeneralException("Qty baru dari {$produk->nama} pada saat transaksi tidak boleh kurang dari 0.");
//     }

//     // saldo produk aktual, dalam satuan terkecil
//     $saldoProduk = InventoryFunction::getStok($cabang_id, $produk_id, $gudang_id, expired_date: $expired_date, no_batch: $no_batch);

//     if ($saldoProduk + $jumlah < 0) {
//         throw new GeneralException("Qty baru dari {$produk->nama} tidak boleh kurang dari 0.");
//     }

//     return MutasiStok::create($data);
// }

// public static function decreaseLatestExpiredDate(
//     $tanggal,
//     $cabang_id,
//     $reference_type,
//     $reference_id,
//     $header_type,
//     $header_id,
//     $jenis_transaksi,
//     $gudang_id,
//     $produk_id,
//     $satuan_transaksi_id,
//     $jumlah_transaksi,
//     $keterangan,
// ): bool {
//     $produk = Produk::with(['jenisProduk'])->findOrFail($produk_id);

//     // jika produk tidak memiliki stok, tidak perlu di proses mutasi stok
//     if ($produk->jenisProduk->nama == Const_Umum::JENIS_PRODUK_JASA) {
//         return true;
//     }

//     if ($jumlah_transaksi >= 0) {
//         throw new GeneralException('Jumlah mutasi stok harus lebih kecil dari 0.');
//     }

//     if (!in_array($satuan_transaksi_id, $produk->satuans->pluck('id')->all())) {
//         throw new GeneralException('Satuan mutasi stok tidak terdaftar disatuan produk.');
//     }

//     $tanggal = _datetime_carbon_db($tanggal);

//     // dalam satuan terkecil
//     $produkSatuan = ProdukSatuan::query()
//         ->where('produk_id', $produk_id)
//         ->where('satuan_id', $satuan_transaksi_id)
//         ->first();

//     if (!$produkSatuan) {
//         throw new GeneralException('Satuan mutasi stok tidak terdaftar disatuan produk.');
//     }

//     $satuan_id = $produk->produkSatuan->satuan_id;
//     $konversi = $produkSatuan->konversi;
//     $jumlah = $konversi * $jumlah_transaksi;
//     $harga = InventoryFunction::getHpp($cabang_id, $produk_id, $tanggal);
//     $harga_transaksi = _round($harga * $konversi);
//     $dataStokByExpiredDates = InventoryFunction::getStokGroupByExpiredDate($cabang_id, $produk_id, $satuan_id, $gudang_id);
//     foreach ($dataStokByExpiredDates as $dataStokByExpiredDate) {
//         $dataStokByNoBatchs = InventoryFunction::getStokGroupByNoBatch($cabang_id, $produk_id, $satuan_id, $gudang_id, $dataStokByExpiredDate->expired_date);
//         foreach ($dataStokByNoBatchs as $dataStokByNoBatch) {
//             if ($dataStokByNoBatch->total_jumlah == 0) {
//                 continue;
//             }
//             $jumlah_simpan = 0;
//             if ($jumlah + $dataStokByNoBatch->total_jumlah < 0) {
//                 $jumlah_simpan = -$dataStokByNoBatch->total_jumlah;
//                 $jumlah += $dataStokByNoBatch->total_jumlah;
//             } else {
//                 $jumlah_simpan = $jumlah;
//                 $jumlah = 0;
//             }

//             $jumlah_transaksi_simpan = $konversi == 0 ? 0 : $jumlah_simpan / $konversi;

//             $data = [
//                 'tanggal' => $tanggal,
//                 'cabang_id' => $cabang_id,
//                 'reference_type' => $reference_type,
//                 'reference_id' => $reference_id,
//                 'header_type' => $header_type,
//                 'header_id' => $header_id,
//                 'jenis_transaksi' => $jenis_transaksi,
//                 'gudang_id' => $gudang_id,
//                 'produk_id' => $produk_id,
//                 'satuan_id' => $satuan_id,
//                 'satuan_transaksi_id' => $satuan_transaksi_id,
//                 'expired_date' => $dataStokByNoBatch->expired_date,
//                 'no_batch' => $dataStokByNoBatch->no_batch,
//                 'jumlah' => $jumlah_simpan,
//                 'jumlah_transaksi' => $jumlah_transaksi_simpan,
//                 'harga' => $harga,
//                 'harga_transaksi' => $harga_transaksi,
//                 'keterangan' => $keterangan,
//             ];

//             // saldo produk pada saat transaksi
//             $saldoProdukTransaksi = InventoryFunction::getStok($cabang_id, $produk_id, $gudang_id, $tanggal);
//             if ($saldoProdukTransaksi + $jumlah < 0) {
//                 throw new GeneralException("Qty baru dari {$produk->nama} pada saat transaksi tidak boleh kurang dari 0.");
//             }

//             // saldo produk aktual, dalam satuan terkecil
//             $saldoProduk = InventoryFunction::getStok($cabang_id, $produk_id, $gudang_id);

//             if ($saldoProduk + $jumlah < 0) {
//                 throw new GeneralException("Qty baru dari {$produk->nama} tidak boleh kurang dari 0.");
//             }
//             MutasiStok::create($data);

//             if ($jumlah == 0) {
//                 break;
//             }
//         }
//         if ($jumlah == 0) {
//             break;
//         }
//     }
//     if ($jumlah != 0) {
//         throw new GeneralException("Qty baru dari {$produk->nama} pada saat transaksi tidak boleh kurang dari 0.");
//     }

//     dd($produk->jenisProduk->nama);
//     return true;
// }

// public static function destroy(?MutasiStok $mutasiStok = null): bool
// {
//     if (!$mutasiStok) {
//         return true;
//     }

//     $produk = $mutasiStok->produk;

//     // saldo produk pada saat transaksi, dalam satuan terkecil
//     $saldoProdukTransaksi = InventoryFunction::getStok($mutasiStok->cabang_id, $produk->id, $mutasiStok->gudang_id, _datetime_carbon_db($mutasiStok->tanggal), expired_date: _date_format_db($mutasiStok->expired_date), no_batch: $mutasiStok->no_batch);

//     if ($saldoProdukTransaksi < $mutasiStok->jumlah) {
//         throw new GeneralException("Qty baru dari {$produk->nama} pada saat transaksi tidak boleh kurang dari 0.");
//     }

//     // saldo produk aktual, dalam satuan terkecil
//     $saldoProduk = InventoryFunction::getStok($mutasiStok->cabang_id, $produk->id, $mutasiStok->gudang_id, expired_date: _date_format_db($mutasiStok->expired_date), no_batch: $mutasiStok->no_batch);
//     if ($saldoProduk < $mutasiStok->jumlah) {
//         throw new GeneralException("Qty baru dari {$produk->nama} tidak boleh kurang dari 0.");
//     }

//     return $mutasiStok->delete();
// }
// }
