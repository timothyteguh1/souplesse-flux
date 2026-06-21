<?php

namespace App\Livewire\Admin\System\Accurate;

use App\Models\Master\Perusahaan;
use App\Services\AccurateService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public ?Perusahaan $perusahaan = null;
    public array $databases = [];
    public ?string $selectedDbId = null;
    public ?string $selectedDbAlias = null;
    public ?string $selectedDbHost = null;

    public function mount(AccurateService $accurateService)
    {
        $this->perusahaan = Perusahaan::first();
        
        if ($this->perusahaan && $this->perusahaan->accurate_access_token && !$this->perusahaan->accurate_db_id) {
            $this->databases = $accurateService->getDatabaseList($this->perusahaan);
        }
    }

    public function loadDatabases()
    {
        $service = app(AccurateService::class);
        $this->databases = $service->getDatabaseList($this->perusahaan);
    }

    public function selectDatabase()
    {
        $this->validate([
            'selectedDbId' => 'required',
        ]);

        $db = collect($this->databases)->firstWhere('id', $this->selectedDbId);
        
        if (!$db) {
            $this->dispatch('toast', type: 'error', message: 'Database tidak ditemukan.');
            return;
        }

        $service = app(AccurateService::class);
        
        $success = $service->selectDatabase($this->perusahaan, $db['id'], $db['alias']);

        if ($success) {
            $this->perusahaan->refresh();
            $this->dispatch('toast', type: 'success', message: 'Database Accurate berhasil dipilih dan dihubungkan!');
        } else {
            $this->dispatch('toast', type: 'error', message: 'Gagal menghubungkan database. Silakan cek log sistem.');
        }
    }

    public function disconnect()
    {
        if ($this->perusahaan) {
            $this->perusahaan->update([
                'accurate_access_token'     => null,
                'accurate_refresh_token'    => null,
                'accurate_token_expires_at' => null,
                'accurate_db_id'            => null,
                'accurate_db_alias'         => null,
                'accurate_host'             => null,
            ]);
        }
        
        $this->databases = [];
        $this->dispatch('toast', type: 'success', message: 'Koneksi Accurate berhasil diputus.');
    }

    // =========================================================================
    // FUNGSI SINKRONISASI SEMUA MASTER DATA (TOMBOL GABUNGAN)
    // =========================================================================
    public function syncMasterData()
    {
        $this->syncAll(); 
        $this->syncCustomers(); 
        $this->syncSuppliers(); 
        $this->syncKaryawans();
        $this->syncPesananPenjualans();
    }

    // =========================================================================
    // FUNGSI SINKRONISASI PRODUK (BARANG & JASA)
    // =========================================================================
    public function syncAll()
    {
        try {
            $service = app(AccurateService::class);
            
            $items = $service->getItems($this->perusahaan);

            if ($items === false) {
                $this->dispatch('toast', type: 'error', message: 'Gagal menarik data dari API Accurate. Cek log sistem.');
                return;
            }

            $cabangId = session('cabang_id');

            if (!$cabangId) {
                $this->dispatch('toast', type: 'error', message: 'Sistem tidak dapat mendeteksi Cabang yang aktif. Pastikan Anda sudah memilih Cabang.');
                return;
            }

            $count = 0;
            foreach ($items as $item) {
                $kategoriId = null;
                if (isset($item['itemCategory']['name'])) {
                    $kategori = \App\Models\Master\KategoriProduk::firstOrCreate(
                        ['nama' => $item['itemCategory']['name'], 'cabang_id' => $cabangId]
                    );
                    $kategoriId = $kategori->id;
                }

                $satuanId = null;
                if (!empty($item['unit1Name'])) {
                    $satuan = \App\Models\Master\Satuan::firstOrCreate(
                        ['nama' => $item['unit1Name'], 'cabang_id' => $cabangId]
                    );
                    $satuanId = $satuan->id;
                }

                \App\Models\Master\Produk::updateOrCreate(
                    ['accurate_id' => $item['id']], 
                    [
                        'kode'        => $item['no'] ?? null,
                        'nama'        => $item['name'] ?? null,
                        'cabang_id'   => $cabangId,
                        'harga_jual'  => $item['unitPrice'] ?? 0,
                        'harga_beli'  => $item['vendorPrice'] ?? 0, 
                        'stok'        => $item['quantity'] ?? 0,
                        'kategori_produk_id' => $kategoriId,
                        'default_satuan_beli_id' => $satuanId, 
                        'default_satuan_jual_id' => $satuanId,
                    ]
                );
                $count++;
            }

            $this->dispatch('toast', type: 'success', message: "$count Data Produk berhasil disinkronkan dari Accurate!");
            
        } catch (\Exception $e) {
            Log::error('Accurate Sync Produk Error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem saat sinkronisasi Produk.');
        }
    }
    // =========================================================================
    // FUNGSI SINKRONISASI PESANAN PENJUALAN (SO) - PULL DARI ACCURATE
    // =========================================================================
    public function syncPesananPenjualans()
    {
        try {
            $service = app(AccurateService::class);
            
            // 1. Tarik List SO dari Accurate
            $response = $service->apiGet($this->perusahaan, '/sales-order/list.do', [
                'fields' => 'id,number,transDate,description'
            ]);

            if (!$response || !isset($response['d'])) return;

            $salesOrders = $response['d'];
            $cabangId = session('cabang_id');

            if (!$cabangId) return;

            $count = 0;
            foreach ($salesOrders as $item) {
                try {
                    // Cek apakah SO ini sudah ada di database lokal kita
                    $soLokal = \App\Models\Penjualan\PesananPenjualan::where('accurate_id', $item['id'])->first();
                    if ($soLokal) {
                        continue; // Jika sudah ada, lewati (agar tidak dobel)
                    }

                    // 2. Ketuk Pintu Detail untuk mengambil Customer, Sales, dan Barang
                    $detailResponse = $service->apiGet($this->perusahaan, '/sales-order/detail.do', [
                        'id' => $item['id']
                    ]);
                    $detail = $detailResponse['d'] ?? $item;

                    // Cari ID Customer Lokal berdasarkan Kode dari Accurate
                    $customerNo = $detail['customer']['no'] ?? null;
                    $customer = \App\Models\Master\Customer::where('kode', $customerNo)->first();
                    if (!$customer) continue; // Wajib ada customer di lokal, jika tidak lewati

                    // Cari ID Sales Lokal berdasarkan Kode dari Accurate
                    $employeeNo = $detail['employee']['no'] ?? null;
                    $karyawan = \App\Models\Master\Karyawan::where('kode', $employeeNo)->first();
                    
                    // Susun Data Barang (Detail Item)
                    $items = [];
                    if (isset($detail['detailItem'])) {
                        foreach ($detail['detailItem'] as $det) {
                            // Cari barang lokal berdasarkan SKU Accurate
                            $itemNo = $det['item']['no'] ?? null;
                            $produk = \App\Models\Master\Produk::where('kode', $itemNo)->first();
                            
                            if ($produk) {
                                $items[] = [
                                    'produk_id' => $produk->id,
                                    'qty'       => $det['quantity'] ?? 1,
                                    'harga'     => $det['unitPrice'] ?? 0,
                                ];
                            }
                        }
                    }

                    // Jika tidak ada barang yang nyangkut/cocok di lokal, lewati SO ini
                    if (empty($items)) continue;

                    // 3. Simpan Header SO ke database Lokal secara langsung (Bypass Service)
                    $tanggalSO = isset($detail['transDate']) ? \Carbon\Carbon::createFromFormat('d/m/Y', $detail['transDate'])->format('Y-m-d H:i:s') : now();

                    $soBaru = \App\Models\Penjualan\PesananPenjualan::create([
                        'accurate_id'     => $detail['id'],
                        'cabang_id'       => $cabangId,
                        'kode'            => $detail['number'] ?? 'SO-ACC-' . $detail['id'],
                        'tanggal'         => $tanggalSO,
                        'customer_id'     => $customer->id,
                        'karyawan_id'     => $karyawan ? $karyawan->id : null,
                        'keterangan'      => $detail['description'] ?? null,
                        
                        // Default Setting
                        'jenis_transaksi' => \App\Utilities\Constants\Const_Umum::JENIS_TRANSAKSI_PESANAN_PENJUALAN ?? 'Pesanan Penjualan',
                        'is_pkp'          => isset($detail['tax1Name']) ? 1 : 0,
                        'is_include_ppn'  => ($detail['inclusiveTax'] ?? false) ? 1 : 0,
                        'ppn_percent'     => isset($detail['tax1Name']) ? 11 : 0, 
                        'diskon_type'     => \App\Utilities\Constants\Const_Umum::DISKON_TYPE_RP ?? 'rp',
                        'diskon'          => $detail['cashDiscount'] ?? 0,
                        'biaya_lain'      => 0,
                        'status'          => \App\Utilities\Constants\Const_Status::PESANAN_PENJUALAN_BELUM_SELESAI ?? 'Belum Selesai',
                    ]);

                    // 4. Simpan Detail Barang ke database Lokal
                    foreach($items as $it) {
                        $it['pesanan_penjualan_id'] = $soBaru->id;
                        \App\Models\Penjualan\PesananPenjualanDetail::create($it);
                    }
                    
                    $count++;
                    
                } catch (\Exception $e) {
                    Log::error("Gagal pull SO {$item['number']} dari Accurate: " . $e->getMessage());
                    continue; 
                }
            }

            $this->dispatch('toast', type: 'success', message: "$count Pesanan Penjualan baru berhasil ditarik dari Accurate!");
            
        } catch (\Exception $e) {
            Log::error('Accurate Sync SO Pull Error: ' . $e->getMessage());
        }
    }

    // =========================================================================
    // FUNGSI SINKRONISASI CUSTOMER (PELANGGAN)
    // =========================================================================
    public function syncCustomers()
    {
        try {
            $service = app(AccurateService::class);
            
            $response = $service->apiGet($this->perusahaan, '/customer/list.do', [
                'fields' => 'id,name,customerNo,workPhone,mobilePhone,email,billStreet,billCity,notes'
            ]);

            if (!$response || !isset($response['d'])) {
                $this->dispatch('toast', type: 'error', message: 'Gagal menarik data Customer dari API Accurate.');
                return;
            }

            $customers = $response['d'];
            $cabangId = session('cabang_id');

            if (!$cabangId) return;

            $count = 0;
            foreach ($customers as $item) {
                try {
                    $kodeCustomer = !empty($item['customerNo']) ? $item['customerNo'] : 'C-ACC-' . $item['id'];
                    $namaCustomer = !empty($item['name']) ? $item['name'] : 'Tanpa Nama';

                    \App\Models\Master\Customer::updateOrCreate(
                        [
                            'accurate_id' => $item['id']
                        ], 
                        [
                            'kode'       => $kodeCustomer,
                            'nama'       => $namaCustomer,
                            'cabang_id'  => $cabangId,
                            'telp'       => $item['workPhone'] ?? null,
                            'handphone'  => $item['mobilePhone'] ?? null,
                            'email'      => $item['email'] ?? null,
                            'alamat'     => $item['billStreet'] ?? null,
                            'kota'       => $item['billCity'] ?? null,
                            'keterangan' => $item['notes'] ?? null,
                        ]
                    );
                    $count++;
                } catch (\Exception $e) {
                    Log::error("Gagal simpan customer {$item['name']} dari Accurate: " . $e->getMessage());
                    continue; 
                }
            }

            $this->dispatch('toast', type: 'success', message: "$count Data Customer berhasil disinkronkan dari Accurate!");
            
        } catch (\Exception $e) {
            Log::error('Accurate Sync Customer Error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem saat sinkronisasi Customer.');
        }
    }

    // =========================================================================
    // FUNGSI SINKRONISASI SUPPLIER (PEMASOK)
    // =========================================================================
    public function syncSuppliers()
    {
        try {
            $service = app(AccurateService::class);
            
            $response = $service->apiGet($this->perusahaan, '/vendor/list.do', [
                'fields' => 'id,name,vendorNo'
            ]);

            if (!$response || !isset($response['d'])) {
                $this->dispatch('toast', type: 'error', message: 'Gagal menarik data Supplier dari API Accurate.');
                return;
            }

            $suppliers = $response['d'];
            $cabangId = session('cabang_id');

            if (!$cabangId) return;

            $count = 0;
            foreach ($suppliers as $item) {
                try {
                    $detailResponse = $service->apiGet($this->perusahaan, '/vendor/detail.do', [
                        'id' => $item['id']
                    ]);

                    $detail = $detailResponse['d'] ?? $item;

                    $kodeSupplier = !empty($detail['vendorNo']) ? $detail['vendorNo'] : 'V-ACC-' . $detail['id'];
                    $namaSupplier = !empty($detail['name']) ? $detail['name'] : 'Tanpa Nama';

                    \App\Models\Master\Supplier::updateOrCreate(
                        [
                            'accurate_id' => $detail['id']
                        ], 
                        [
                            'kode'       => $kodeSupplier,
                            'nama'       => $namaSupplier,
                            'cabang_id'  => $cabangId,
                            'telp'       => $detail['workPhone'] ?? null,
                            'handphone'  => $detail['mobilePhone'] ?? null,
                            'email'      => $detail['email'] ?? null,
                            'alamat'     => $detail['billStreet'] ?? null,
                            'kota'       => $detail['billCity'] ?? null,
                            'keterangan' => $detail['notes'] ?? null,
                        ]
                    );
                    $count++;
                    
                } catch (\Exception $e) {
                    Log::error("Gagal simpan supplier {$item['name']} dari Accurate: " . $e->getMessage());
                    continue; 
                }
            }

            $this->dispatch('toast', type: 'success', message: "$count Data Supplier berhasil disinkronkan dari Accurate!");
            
        } catch (\Exception $e) {
            Log::error('Accurate Sync Supplier Error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem saat sinkronisasi Supplier.');
        }
    }

    // =========================================================================
    // FUNGSI SINKRONISASI KARYAWAN (SALESMAN)
    // =========================================================================
    public function syncKaryawans()
    {
        try {
            $service = app(AccurateService::class);
            
            // Tarik list karyawan (Employee) dari Accurate
            $response = $service->apiGet($this->perusahaan, '/employee/list.do', [
                'fields' => 'id,name,no'
            ]);

            if (!$response || !isset($response['d'])) return;

            $karyawans = $response['d'];
            $cabangId = session('cabang_id');

            if (!$cabangId) return;

            $count = 0;
            foreach ($karyawans as $item) {
                try {
                    // Ketuk pintu ke endpoint detail agar data kontak dan alamat terbawa
                    $detailResponse = $service->apiGet($this->perusahaan, '/employee/detail.do', [
                        'id' => $item['id']
                    ]);
                    
                    $detail = $detailResponse['d'] ?? $item;

                    $kodeKaryawan = !empty($detail['no']) ? $detail['no'] : 'K-ACC-' . $detail['id'];
                    $namaKaryawan = !empty($detail['name']) ? $detail['name'] : 'Tanpa Nama';

                    \App\Models\Master\Karyawan::updateOrCreate(
                        ['accurate_id' => $detail['id']], 
                        [
                            'kode'       => $kodeKaryawan,
                            'nama'       => $namaKaryawan,
                            'cabang_id'  => $cabangId,
                            'telp'       => $detail['workPhone'] ?? null,
                            'handphone'  => $detail['mobilePhone'] ?? null,
                            'email'      => $detail['email'] ?? null,
                            
                            // Jurus sapu jagat alamat
                            'alamat'     => $detail['billStreet'] ?? $detail['street'] ?? $detail['address'] ?? null,
                            'kota'       => $detail['billCity'] ?? $detail['city'] ?? null,
                            
                            'keterangan' => $detail['notes'] ?? null,
                        ]
                    );
                    $count++;
                    
                } catch (\Exception $e) {
                    Log::error("Gagal simpan Karyawan {$item['name']} dari Accurate: " . $e->getMessage());
                    continue; 
                }
            }

            $this->dispatch('toast', type: 'success', message: "$count Data Karyawan berhasil disinkronkan dari Accurate!");
            
        } catch (\Exception $e) {
            Log::error('Accurate Sync Karyawan Error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem saat sinkronisasi Karyawan.');
        }
    }

    public function render()
    {
        return view('livewire.admin.system.accurate.index')
            ->layout('admin.components.layouts.app');
    }
}