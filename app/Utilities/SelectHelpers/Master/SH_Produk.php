<?php

namespace App\Utilities\SelectHelpers\Master;

use App\Models\Master\Produk;
use App\Models\Master\Satuan;
use App\Utilities\Constants\Const_Umum;
use App\Utilities\Constants\Const_Status;
use App\Utilities\Functions\InventoryFunction;

class SH_Produk
{
    public static function active()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Produk::query()
            ->where('status', Const_Status::AKTIF)
            ->where('cabang_id', $cabang_id)
            ->orderBy('nama')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s",
                $obj->kode,
                $obj->nama,
            );
        }

        return $results;
    }

    public static function activeSupplier($supplier_id)
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Produk::query()
            ->with(['kategoriProduk', 'brandProduk'])
            ->where('status', Const_Status::AKTIF)
            ->where('supplier_id', $supplier_id)
            ->where('cabang_id', $cabang_id)
            ->orderBy('nama')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s -- %s -- %s",
                $obj->kode,
                $obj->nama,
                $obj->kategoriProduk?->nama ?? "Tanpa Kategori Produk",
                $obj->brandProduk?->nama ?? "Tanpa Brand",
            );
        }

        return $results;
    }

    public static function activeSuppliers($supplier_ids)
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Produk::query()
            ->with(['kategoriProduk', 'brandProduk'])
            ->where('status', Const_Status::AKTIF)
            ->whereIn('supplier_id', $supplier_ids)
            ->where('cabang_id', $cabang_id)
            ->orderBy('nama')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s -- %s -- %s",
                $obj->kode,
                $obj->nama,
                $obj->kategoriProduk?->nama ?? "Tanpa Kategori Produk",
                $obj->brandProduk?->nama ?? "Tanpa Brand",
            );
        }

        return $results;
    }

    public static function activeWithStok()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Produk::query()
            ->where('cabang_id', $cabang_id)
            // ->where('is_stok', true)
            ->whereHas('jenisProduk', function ($query) {
                $query->whereIn('nama', [Const_Umum::JENIS_PRODUK_PERSEDIAAN, Const_Umum::JENIS_PRODUK_PAKET]);
            })
            ->where('status', Const_Status::AKTIF)
            ->orderBy('kode')
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                "[%s] -- %s",
                $obj->kode,
                $obj->nama,
            );
        }

        return $results;
    }

    public static function activeWithoutStok()
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Produk::query()
            ->with(['produkSatuan', 'produkSatuan.satuan'])
            ->where('cabang_id', $cabang_id)
            // ->where('is_stok', false)
            ->whereHas('jenisProduk', function ($query) {
                $query->whereIn('nama', [Const_Umum::JENIS_PRODUK_PERSEDIAAN, Const_Umum::JENIS_PRODUK_PAKET]);
            })
            ->where('status', Const_Status::AKTIF)
            ->get();

        $results = [];
        foreach ($objs as $obj) {
            $results[$obj->id] = sprintf(
                '[%s] -- %s',
                $obj->kode,
                $obj->nama,
            );
        }

        return $results;
    }

    public static function stokGudangWithStok($gudang_id, $hanyaPunyaStok = true, $include_ids = [], $cabang_id = null)
    {
        if ($cabang_id == null) {
            $cabang_id = session()->get('cabang_id');
        }

        $objs = Produk::query()
            //            ->with(['produkSatuan', 'produkSatuan.satuan'])
            //            ->where('cabang_id', $cabang_id)
            ->whereHas('jenisProduk', function ($query) {
                $query->whereIn('nama', [Const_Umum::JENIS_PRODUK_PERSEDIAAN, Const_Umum::JENIS_PRODUK_PAKET]);
            })
            ->where('status', Const_Status::AKTIF)
            ->get();

        $results = [];
        $produk_ids = $objs->pluck('id')->toArray();
        $stoks = InventoryFunction::getStoks($cabang_id, $produk_ids, $gudang_id);

        if ($gudang_id) {
            foreach ($objs as $obj) {
                $stok = $stoks[$obj->id] ?? 0;

                if ($hanyaPunyaStok && $stok != 0 || in_array($obj->id, $include_ids)) {
                    $results[$obj->id] = sprintf(
                        '[%s] -- %s -- Tersedia: %s',
                        $obj->kode,
                        $obj->nama,
                        _number($stok),
                        //                        $obj->produkSatuan->satuan->nama,
                    );
                } elseif (!$hanyaPunyaStok) {
                    $results[$obj->id] = sprintf(
                        '[%s] -- %s -- Tersedia: %s',
                        $obj->kode,
                        $obj->nama,
                        _number($stok),
                        //                        $obj->produkSatuan->satuan->nama,
                    );
                }
            }
        }

        return $results;
    }

    public static function stokCabangWithStok($hanyaPunyaStok = true)
    {
        $cabang_id = session()->get('cabang_id');

        $objs = Produk::query()
            ->with(['satuan'])
            ->where('cabang_id', $cabang_id)
            ->where('status', Const_Status::AKTIF)
            ->get();

        $results = [];

        foreach ($objs as $obj) {
            $stok = InventoryFunction::getStok($cabang_id, $obj->id);
            if ($hanyaPunyaStok && $stok != 0) {
                $results[$obj->id] = sprintf(
                    '[%s -- %s] -- Tersedia: %s %s',
                    $obj->kode,
                    $obj->nama,
                    _number($stok),
                    $obj->satuan->nama,
                );
            } elseif (!$hanyaPunyaStok) {
                $results[$obj->id] = sprintf(
                    '[%s -- %s] -- Tersedia: %s %s',
                    $obj->kode,
                    $obj->nama,
                    _number($stok),
                    $obj->satuan->nama,
                );
            }
        }

        return $results;
    }

    public static function satuansStokGudang($produk_id, $gudang_id, $showTersedia = true)
    {
        $cabang_id = session()->get('cabang_id');
        $produk = Produk::findOrFail($produk_id);

        $results = [];
        foreach ($produk->with(['satuan'])->get() as $produkSatuan) {
            $stok = InventoryFunction::getStok($cabang_id, $produk_id, $gudang_id);

            if (!$showTersedia) {
                $results[$produkSatuan->satuan_id] = sprintf(
                    '[%s] -- %s',
                    $produkSatuan->satuan->kode,
                    $produkSatuan->satuan->nama,
                );
            } else {
                $results[$produkSatuan->satuan_id] = sprintf(
                    '%s -- Tersedia: %s %s',
                    $produkSatuan->satuan->nama,
                    _number(InventoryFunction::getStokSatuan($produk_id, $produkSatuan->satuan_id, $stok)),
                    $produkSatuan->satuan->nama,
                );
            }
        }

        return $results;
    }

    public static function satuansStokCabang($produk_id, $showTersedia = true)
    {
        $cabang_id = session()->get('cabang_id');
        $produk = Produk::findOrFail($produk_id);

        $results = [];
        foreach ($produk->with(['satuan'])->get() as $produkSatuan) {
            $stok = InventoryFunction::getStok($cabang_id, $produk_id);

            if (!$showTersedia) {
                $results[$produkSatuan->satuan_id] = sprintf(
                    '[%s] -- %s',
                    $produkSatuan->satuan->kode,
                    $produkSatuan->satuan->nama,
                );
            } else {
                $results[$produkSatuan->satuan_id] = sprintf(
                    '%s -- Tersedia: %s %s',
                    $produkSatuan->satuan->nama,
                    _number(InventoryFunction::getStokSatuan($produk_id, $produkSatuan->satuan_id, $stok)),
                    $produkSatuan->satuan->nama,
                );
            }
        }

        return $results;
    }

    public static function expiredDatesStokGudang($produk_id, $gudang_id, $satuan_id, $showTersedia = true)
    {
        $cabang_id = session()->get('cabang_id');
        $satuan = Satuan::findOrFail($satuan_id);

        $results = [];
        $data = InventoryFunction::getStokGroupByExpiredDate($cabang_id, $produk_id, $satuan_id, $gudang_id);
        foreach ($data as $value) {
            if (!$showTersedia) {
                $results[$value->expired_date ?: "-"] = sprintf(
                    '[%s]',
                    $value->expired_date ?: "Tanpa Expired Date",
                );
            } else {
                $results[$value->expired_date ?: "-"] = sprintf(
                    '[%s] -- Tersedia: %s %s',
                    $value->expired_date ?: "Tanpa Expired Date",
                    _number($value->total_jumlah),
                    $satuan->nama,
                );
            }
        }
        return $results;
    }

    public static function noBatchStokGudang($produk_id, $gudang_id, $satuan_id, $expired_date, $showTersedia = true)
    {
        $cabang_id = session()->get('cabang_id');
        $satuan = Satuan::findOrFail($satuan_id);
        $expired_date = $expired_date == "-" ? null : $expired_date;

        $results = [];
        $data = InventoryFunction::getStokGroupByNoBatch($cabang_id, $produk_id, $satuan_id, $gudang_id, $expired_date);
        foreach ($data as $value) {
            if (!$showTersedia) {
                $results[$value->no_batch ?: "-"] = sprintf(
                    '[%s]',
                    $value->no_batch ?: "Tanpa No Batch",
                );
            } else {
                $results[$value->no_batch ?: "-"] = sprintf(
                    '[%s] -- Tersedia: %s %s',
                    $value->no_batch ?: "Tanpa No Batch",
                    _number($value->total_jumlah),
                    $satuan->nama,
                );
            }
        }

        return $results;
    }
}
