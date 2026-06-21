<?php

namespace App\Services\Accurate;

use App\Models\Master\Perusahaan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AccurateTokenService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected string $baseUrl;

    public function __construct()
    {
        $this->clientId = config('accurate.client_id');
        $this->clientSecret = config('accurate.client_secret');
        $this->redirectUri = config('accurate.redirect_uri');
        $this->baseUrl = config('accurate.base_url');
    }

    /**
     * Mendapatkan URL untuk otorisasi OAuth Accurate.
     * Menggunakan parameter 'state' untuk membawa ID Perusahaan.
     */
    public function getAuthorizationUrl(string $perusahaanId): string
    {
        $params = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'redirect_uri' => $this->redirectUri,
            'state' => $perusahaanId, 
            
            // --- PERBAIKAN: Menambahkan employee_view dan employee_save ---
            'scope' => 'item_view item_save customer_view customer_save vendor_view vendor_save employee_view employee_save sales_invoice_view sales_invoice_save purchase_order_view purchase_order_save',
        ]);

        return "{$this->baseUrl}/oauth/authorize?{$params}";
    }

    /**
     * Menukarkan kode otorisasi dengan Access Token dan menyimpannya ke tabel Perusahaan.
     */
    public function exchangeCodeForToken(string $code, Perusahaan $perusahaan): bool
    {
        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/oauth/token", [
                'code' => $code,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $this->redirectUri,
            ]);

        if (!$response->successful()) {
            Log::error('Accurate OAuth exchange error', $response->json() ?? []);
            return false;
        }

        $data = $response->json();

        $perusahaan->update([
            'accurate_access_token' => $data['access_token'],
            'accurate_refresh_token' => $data['refresh_token'] ?? null,
            'accurate_token_expires_at' => now()->addSeconds($data['expires_in'] ?? 3600),
        ]);

        return true;
    }

    /**
     * Memperbarui Access Token menggunakan Refresh Token.
     */
    public function refreshToken(Perusahaan $perusahaan): bool
    {
        if (!$perusahaan->accurate_refresh_token) {
            return false;
        }

        $response = Http::withBasicAuth($this->clientId, $this->clientSecret)
            ->asForm()
            ->post("{$this->baseUrl}/oauth/token", [
                'grant_type' => 'refresh_token',
                'refresh_token' => $perusahaan->accurate_refresh_token,
            ]);

        if (!$response->successful()) {
            Log::error('Accurate refresh token error', $response->json() ?? []);
            return false;
        }

        $data = $response->json();

        $perusahaan->update([
            'accurate_access_token' => $data['access_token'],
            'accurate_refresh_token' => $data['refresh_token'] ?? $perusahaan->accurate_refresh_token,
            'accurate_token_expires_at' => now()->addSeconds($data['expires_in'] ?? 3600),
        ]);

        return true;
    }

    /**
     * Mengambil Access Token yang valid (otomatis refresh jika kedaluwarsa).
     */
    public function getValidToken(Perusahaan $perusahaan): ?string
    {
        if (!$perusahaan->accurate_access_token) {
            return null;
        }

        // Cek apakah token sudah expired
        if (!$perusahaan->accurate_token_expires_at || now()->gte($perusahaan->accurate_token_expires_at)) {
            $refreshed = $this->refreshToken($perusahaan);
            if (!$refreshed) {
                return null;
            }
            $perusahaan->refresh(); // Ambil data terbaru dari DB setelah update
        }

        return $perusahaan->accurate_access_token;
    }
}