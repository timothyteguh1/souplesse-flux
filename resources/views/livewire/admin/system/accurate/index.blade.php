<div>
    <x-slot name="title">Accurate Integration</x-slot>

    <div class="max-w-4xl mx-auto py-8">
        {{-- HEADER --}}
        <div class="mb-8 border-b pb-4">
            <h1 class="text-3xl font-bold text-gray-800">Accurate Integration</h1>
            <p class="text-gray-500 mt-2">Hubungkan sistem dengan Accurate Online untuk sinkronisasi data.</p>
        </div>

        {{-- ALERT MESSAGES --}}
        @if (session('success'))
            <div
                class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 rounded shadow-sm flex items-center">
                <svg style="width: 20px; height: 20px; min-width: 20px;" class="mr-2" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700 rounded shadow-sm flex items-center">
                <svg style="width: 20px; height: 20px; min-width: 20px;" class="mr-2" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        {{-- STATE 1: BELUM CONNECT --}}
        @if (!$token)
            <div class="bg-white rounded-xl shadow p-10 text-center border border-gray-100">
                <div class="mx-auto bg-blue-50 rounded-full flex items-center justify-center mb-6"
                    style="width: 96px; height: 96px;">
                    <svg style="width: 48px; height: 48px;" class="text-blue-500" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-3">Belum Terhubung ke Accurate</h2>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">Klik tombol di bawah untuk diarahkan ke halaman login
                    Accurate dan memberikan izin akses ke sistem.</p>
                <a href="{{ route('accurate.connect') }}"
                    style="background-color: #2563eb; color: #ffffff; padding: 12px 32px; border-radius: 8px; display: inline-flex; align-items: center; font-weight: 600; text-decoration: none;"
                    class="hover:opacity-90 transition shadow">
                    <svg style="width: 20px; height: 20px; margin-right: 8px;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                    </svg>
                    Hubungkan ke Accurate
                </a>
            </div>

         {{-- STATE 2: SUDAH DAPAT TOKEN, BELUM PILIH DATABASE --}}
        @elseif ($token && !$token->is_connected)
            <div class="bg-white rounded-xl shadow p-8 border border-gray-100 max-w-2xl mx-auto">
                <div class="flex items-center mb-6 border-b pb-4" style="border-bottom: 1px solid #e5e7eb; padding-bottom: 16px; margin-bottom: 24px; display: flex; align-items: center;">
                    <div class="bg-yellow-100 rounded-full flex items-center justify-center mr-4" style="background-color: #fef9c3; width: 48px; height: 48px; min-width: 48px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-right: 16px;">
                        <svg style="width: 24px; height: 24px; color: #ca8a04;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800" style="margin: 0; font-size: 1.25rem; color: #1f2937;">Pilih Database Accurate</h2>
                        <p class="text-gray-500 text-sm mt-1" style="margin: 4px 0 0 0; color: #6b7280; font-size: 0.875rem;">Token berhasil didapat. Silakan pilih database yang akan dihubungkan.</p>
                    </div>
                </div>

                @if (count($databases) > 0)
                    <div class="mb-6" style="margin-bottom: 24px;">
                        <label class="block text-sm font-semibold text-gray-700 mb-2" style="display: block; margin-bottom: 8px; font-weight: 600; color: #374151;">Daftar Database Anda</label>
                        <select wire:model="selectedDbId"
                            style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 12px 16px; background-color: #f9fafb; color: #374151; outline: none; cursor: pointer;">
                            <option value="">-- Silakan Pilih Database --</option>
                            @foreach ($databases as $db)
                                <option value="{{ $db['id'] }}">{{ $db['alias'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Container Tombol --}}
                    <div style="display: flex; gap: 12px; margin-top: 32px;">
                        <button wire:click="selectDatabase"
                            style="background-color: #2563eb; color: #ffffff; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer; flex: 1; display: flex; justify-content: center; align-items: center;"
                            class="hover:opacity-90 transition shadow">
                            <span wire:loading.remove wire:target="selectDatabase">Simpan & Hubungkan</span>
                            <span wire:loading wire:target="selectDatabase">Menyimpan...</span>
                        </button>
                        <button wire:click="disconnect"
                            style="background-color: #f3f4f6; color: #4b5563; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;"
                            class="hover:opacity-90 transition">
                            Batalkan
                        </button>
                    </div>
                @else
                    <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200 mb-6" style="text-align: center; padding: 32px; background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; margin-bottom: 24px;">
                        <svg style="width: 48px; height: 48px; color: #9ca3af; margin: 0 auto 12px auto;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <p style="color: #6b7280; font-weight: 500; margin: 0;">Tidak ada database ditemukan di akun Accurate ini.</p>
                    </div>
                    <button wire:click="disconnect"
                        style="width: 100%; background-color: #f3f4f6; color: #374151; padding: 12px 24px; border-radius: 8px; font-weight: 600; border: none; cursor: pointer;"
                        class="hover:opacity-90 transition">
                        Kembali & Putuskan Koneksi
                    </button>
                @endif
            </div>

            {{-- STATE 3: SUDAH CONNECT & PILIH DB --}}
        {{-- STATE 3: SUDAH CONNECT & PILIH DB --}}
        @elseif ($token && $token->is_connected)
            <div style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; border: 1px solid #f3f4f6;">
                
                {{-- Header Card --}}
                <div style="background-color: #f0fdf4; padding: 20px 32px; border-bottom: 1px solid #dcfce3; display: flex; flex-direction: row; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center;">
                        <div style="background-color: #dcfce3; border-radius: 9999px; display: flex; align-items: center; justify-content: center; margin-right: 16px; width: 48px; height: 48px; min-width: 48px;">
                            <svg style="width: 24px; height: 24px; color: #16a34a;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 style="margin: 0; font-size: 1.25rem; font-weight: bold; color: #1f2937;">Koneksi Berhasil</h2>
                            <p style="margin: 4px 0 0 0; color: #16a34a; font-size: 0.875rem; font-weight: 500;">Sistem terhubung ke Accurate Online</p>
                        </div>
                    </div>
                    <button wire:click="disconnect"
                        wire:confirm="Yakin ingin memutus koneksi Accurate? Semua sinkronisasi akan berhenti."
                        style="padding: 10px 20px; background-color: #ffffff; border: 1px solid #fecaca; color: #dc2626; border-radius: 8px; font-weight: 600; font-size: 14px; cursor: pointer;">
                        Putuskan Koneksi
                    </button>
                </div>

                <div style="padding: 32px;">
                    {{-- Info Database --}}
                    <div style="background-color: #f9fafb; border-radius: 12px; padding: 24px; margin-bottom: 40px; border: 1px solid #e5e7eb;">
                        <h3 style="font-size: 0.75rem; font-weight: bold; letter-spacing: 0.05em; color: #9ca3af; text-transform: uppercase; margin: 0 0 16px 0;">Informasi Database Aktif</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 24px;">
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 0.875rem; color: #6b7280;">Nama Database (Alias)</p>
                                <p style="margin: 0; font-weight: bold; color: #1f2937;">{{ $token->db_alias }}</p>
                            </div>
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 0.875rem; color: #6b7280;">Host Server</p>
                                <p style="margin: 0; font-weight: bold; color: #1f2937; word-break: break-all;">{{ $token->db_host }}</p>
                            </div>
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 0.875rem; color: #6b7280;">Batas Waktu Token</p>
                                <p style="margin: 0; font-weight: bold; color: {{ $token->isExpired() ? '#dc2626' : '#1f2937' }};">
                                    {{ $token->expires_at?->format('d M Y, H:i') ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Sync Section (All In One) --}}
                    <div style="text-align: center; background-color: #eff6ff; border-radius: 16px; padding: 32px; border: 1px solid #dbeafe;">
                        <div style="margin: 0 auto 16px auto; background-color: #dbeafe; border-radius: 16px; display: flex; align-items: center; justify-content: center; width: 64px; height: 64px; transform: rotate(3deg);">
                            <svg style="width: 32px; height: 32px; color: #2563eb; transform: rotate(-3deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        </div>
                        <h3 style="margin: 0 0 8px 0; font-size: 1.5rem; font-weight: bold; color: #1f2937;">Sinkronisasi Master Data</h3>
                        <p style="margin: 0 auto 32px auto; color: #6b7280; max-width: 32rem; line-height: 1.625;">
                            Tarik seluruh data terbaru dari Accurate (Produk, Customer, Supplier, Kategori, Faktur, dll) sekaligus. Proses ini mungkin membutuhkan waktu beberapa menit.
                        </p>
                        
                        <button wire:click="syncAll"
                            style="background-color: #2563eb; color: #ffffff; padding: 16px 32px; border-radius: 12px; font-weight: bold; font-size: 1.125rem; border: none; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; min-width: 300px; box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);">
                            
                            {{-- Ikon Default --}}
                            <svg style="width: 24px; height: 24px; margin-right: 12px;" wire:loading.remove wire:target="syncAll" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                            </svg>
                            
                            {{-- Ikon Loading Spinner --}}
                            <svg style="width: 24px; height: 24px; margin-right: 12px; color: #ffffff;" wire:loading wire:target="syncAll" class="animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>

                            <span wire:loading.remove wire:target="syncAll">Mulai Tarik Semua Data</span>
                            <span wire:loading wire:target="syncAll">Sedang Menyinkronkan...</span>
                        </button>

                        <div wire:loading wire:target="syncAll" style="margin-top: 20px; font-size: 0.875rem; color: #2563eb; font-weight: 500;">
                            Mohon jangan tutup halaman ini sampai proses selesai.
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>