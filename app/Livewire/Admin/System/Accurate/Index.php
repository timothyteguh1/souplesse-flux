<?php

namespace App\Livewire\Admin\System\Accurate;

use App\Models\AccurateToken;
use App\Services\AccurateService;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class Index extends Component
{
    public ?AccurateToken $token = null;
    public array $databases = [];
    public ?string $selectedDbId = null;
    public ?string $selectedDbAlias = null;
    public ?string $selectedDbHost = null;

    public function mount(AccurateService $accurateService)
    {
        $this->token = AccurateToken::first(); // Memakai first() lebih aman jika tidak ada custom logic getInstance()
        
        if ($this->token && !$this->token->is_connected) {
            $this->databases = $accurateService->getDatabaseList();
        }
    }

    public function loadDatabases()
    {
        $service = app(AccurateService::class);
        $this->databases = $service->getDatabaseList();
    }

    public function selectDatabase()
    {
        $this->validate([
            'selectedDbId' => 'required',
        ]);

        // Cari db dari list
        $db = collect($this->databases)->firstWhere('id', $this->selectedDbId);
        
        if (!$db) {
            $this->dispatch('toast', type: 'error', message: 'Database tidak ditemukan.');
            return;
        }

        $service = app(AccurateService::class);
        
        // Panggil service yang sudah kita update sebelumnya
        $success = $service->selectDatabase($db['id'], $db['alias']);

        if ($success) {
            // Update UI token state
            $this->token = AccurateToken::first();
            $this->dispatch('toast', type: 'success', message: 'Database Accurate berhasil dipilih dan dihubungkan!');
        } else {
            $this->dispatch('toast', type: 'error', message: 'Gagal menghubungkan database. Silakan cek log sistem.');
        }
    }

    public function disconnect()
    {
        $service = app(AccurateService::class);
        $service->disconnect();
        
        $this->token = null;
        $this->databases = [];
        
        $this->dispatch('toast', type: 'success', message: 'Koneksi Accurate berhasil diputus.');
    }

    /**
     * Method baru untuk menangani tombol "Sync Semua Data" dari UI
     */
    public function syncAll()
    {
        try {
            $service = app(AccurateService::class);
            
            // 1. Tarik data dari Accurate
            $items = $service->getItems();

            if ($items === false) {
                $this->dispatch('toast', type: 'error', message: 'Gagal menarik data dari API Accurate. Cek log sistem.');
                return;
            }

            $count = 0;
            foreach ($items as $item) {
                // 2. Simpan ke database lokal menggunakan model yang benar
                \App\Models\Master\Produk::updateOrCreate(
                    // Syarat pencarian: ID dari Accurate
                    ['accurate_id' => $item['id']], 
                    [
                        // Isi data produk
                        'kode' => $item['no'] ?? null,
                        'nama' => $item['name'] ?? null,
                        
                        // Kolom relasi seperti kategori_produk_id dan satuan_id 
                        // dibiarkan kosong (null) dulu sampai kita buat fungsi mapping-nya.
                        // Pastikan kolom ini diizinkan kosong (nullable) di databasemu.
                    ]
                );
                $count++;
            }

            $this->dispatch('toast', type: 'success', message: "$count Data Produk berhasil disinkronkan dari Accurate!");
            
        } catch (\Exception $e) {
            Log::error('Accurate Sync Error: ' . $e->getMessage());
            $this->dispatch('toast', type: 'error', message: 'Terjadi kesalahan sistem saat sinkronisasi.');
        }
    }
    public function render()
    {
        return view('livewire.admin.system.accurate.index')
            ->layout('admin.components.layouts.app');
    }
}