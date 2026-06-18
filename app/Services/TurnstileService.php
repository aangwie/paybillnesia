<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TurnstileService
{
    /**
     * Verifikasi token Cloudflare Turnstile dengan Cloudflare API.
     * Turnstile memberikan respon success (boolean).
     *
     * @param string $token Token dari frontend (cf-turnstile-response)
     * @return array ['success' => bool, 'message' => string]
     */
    public static function verify(string $token): array
    {
        $setting = SiteSetting::first();

        // Jika Turnstile tidak diaktifkan, lewati verifikasi
        if (!$setting || !$setting->turnstile_enabled) {
            return ['success' => true, 'message' => 'Cloudflare Turnstile tidak diaktifkan.'];
        }

        $secretKey = $setting->turnstile_secret_key;
        if (empty($secretKey)) {
            return ['success' => false, 'message' => 'Turnstile secret key belum dikonfigurasi.'];
        }

        try {
            $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                'secret' => $secretKey,
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            $body = $response->json();

            if ($body['success'] ?? false) {
                return ['success' => true, 'message' => 'Verifikasi Turnstile berhasil.'];
            }

            $errorCodes = $body['error-codes'] ?? [];
            $errorMessage = !empty($errorCodes) ? implode(', ', $errorCodes) : 'Verifikasi Turnstile gagal.';
            Log::warning('Turnstile verification failed', ['errors' => $errorCodes]);

            return ['success' => false, 'message' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('Turnstile Exception: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Gagal menghubungi Cloudflare: ' . $e->getMessage()];
        }
    }

    /**
     * Validasi Honeypot: jika field terisi, berarti bot.
     */
    public static function validateHoneypot(?string $honeypotValue): bool
    {
        if (!empty($honeypotValue)) {
            Log::warning('Honeypot triggered: bot detected');
            return false;
        }
        return true;
    }

    /**
     * Validasi Waktu Pengisian Form (Time-based).
     */
    public static function validateFormTime(?int $timestamp, int $minSeconds = 3): bool
    {
        if (!$timestamp) {
            return true;
        }

        $elapsed = time() - $timestamp;
        if ($elapsed < $minSeconds) {
            Log::warning('Form time check triggered', [
                'elapsed' => $elapsed,
                'min_seconds' => $minSeconds,
            ]);
            return false;
        }
        return true;
    }

    /**
     * Cek apakah Turnstile sedang aktif.
     */
    public static function isEnabled(): bool
    {
        $setting = SiteSetting::first();
        return $setting && $setting->turnstile_enabled && !empty($setting->turnstile_site_key);
    }

    /**
     * Ambil site key untuk frontend.
     */
    public static function getSiteKey(): ?string
    {
        $setting = SiteSetting::first();
        return $setting ? $setting->turnstile_site_key : null;
    }
}