@extends('layouts.app2')

@section('title', 'Pengaturan Situs')
@section('header', 'Pengaturan Situs')
@section('subheader', 'Kelola informasi Tentang Kami dan Syarat Ketentuan.')

@section('content')
    <div class="max-w-5xl">
        <form action="{{ route('site.update') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <!-- About Us Section -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-lg bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Tentang Kami</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-500 mb-2">Konten Halaman Tentang Kami</label>
                        <textarea name="about_us" rows="10"
                            class="block w-full rounded-xl border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all font-sans"
                            placeholder="Tuliskan sejarah, visi, dan misi layanan Anda...">{{ $setting->about_us }}</textarea>
                        <p class="mt-2 text-xs text-slate-400 italic">Mendukung format teks biasa. Gunakan enter untuk
                            paragraf baru.</p>
                    </div>
                </div>

                <!-- Connection Mode Section -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Protokol Koneksi</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-500 mb-4">Paksa Protokol Koneksi</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label
                                class="relative flex cursor-pointer rounded-xl border border-slate-200 dark:border-slate-700 p-4 focus:outline-none transition-all hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <input type="radio" name="connection_mode" value="auto" class="sr-only" {{ ($setting->connection_mode ?? 'auto') == 'auto' ? 'checked' : '' }}>
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-slate-900 dark:text-white">Automatic</span>
                                    <span class="mt-1 flex items-center text-xs text-slate-50">Gunakan protokol yang sedang
                                        berjalan.</span>
                                </div>
                                <div class="absolute -top-px -right-px h-6 w-6 rounded-tr-xl rounded-bl-xl bg-primary-600 flex items-center justify-center opacity-0 transition-opacity peer-checked:opacity-100"
                                    style="display: none;">
                                    <i class="fas fa-check text-[10px] text-white"></i>
                                </div>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-xl border border-slate-200 dark:border-slate-700 p-4 focus:outline-none transition-all hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <input type="radio" name="connection_mode" value="http" class="sr-only" {{ ($setting->connection_mode ?? 'auto') == 'http' ? 'checked' : '' }}>
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-slate-900 dark:text-white">Force HTTP</span>
                                    <span class="mt-1 flex items-center text-xs text-slate-50">Paksa semua akses menggunakan
                                        HTTP.</span>
                                </div>
                            </label>

                            <label
                                class="relative flex cursor-pointer rounded-xl border border-slate-200 dark:border-slate-700 p-4 focus:outline-none transition-all hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                <input type="radio" name="connection_mode" value="https" class="sr-only" {{ ($setting->connection_mode ?? 'auto') == 'https' ? 'checked' : '' }}>
                                <div class="flex flex-col">
                                    <span class="block text-sm font-bold text-slate-900 dark:text-white">Force HTTPS</span>
                                    <span class="mt-1 flex items-center text-xs text-slate-50">Paksa semua akses menggunakan
                                        HTTPS.</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <style>
                    input[type="radio"]:checked+div,
                    input[type="radio"]:checked~div {
                        border-color: rgb(var(--primary-600));
                    }

                    label:has(input[type="radio"]:checked) {
                        border-color: #3b82f6;
                        background-color: rgba(59, 130, 246, 0.05);
                    }
                </style>

                <!-- Terms & Conditions Section -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-lg bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600">
                                <i class="fas fa-file-contract"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Syarat & Ketentuan</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-500 mb-2">Konten Halaman Syarat dan
                            Ketentuan</label>
                        <textarea name="terms_conditions" rows="10"
                            class="block w-full rounded-xl border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all font-sans"
                            placeholder="Tuliskan aturan penggunaan layanan, kebijakan privasi, dll...">{{ $setting->terms_conditions }}</textarea>
                        <p class="mt-2 text-xs text-slate-400 italic">Pastikan informasi jelas dan transparan untuk
                            pelanggan Anda.</p>
                    </div>
                </div>

                <!-- Mobile API URL Section -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white">Mobile API URL</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">URL untuk koneksi aplikasi mobile
                                    Billnesia</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <label class="block text-sm font-medium text-slate-500 mb-2">Base URL API</label>
                        <input type="url" name="mobile_api_url" value="{{ $setting->mobile_api_url }}"
                            class="block w-full rounded-xl border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all"
                            placeholder="https://yourdomain.com">
                        <p class="mt-2 text-xs text-slate-400 italic">
                            Masukkan URL lengkap server Anda (contoh: https://billing.example.com). URL ini akan digunakan
                            oleh aplikasi mobile Billnesia untuk terhubung ke server.
                        </p>
                    </div>
                </div>

                <!-- Cloudflare Turnstile Section -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600">
                                <i class="fas fa-cloud"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white">Cloudflare Turnstile + Honeypot</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Perlindungan multi-layer terhadap spam registrasi bot</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Status</label>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="turnstile_enabled" value="1" class="sr-only peer" {{ $setting->turnstile_enabled ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-300 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-primary-600"></div>
                                <span class="ms-3 text-sm font-medium text-slate-700 dark:text-slate-300">Aktifkan Cloudflare Turnstile</span>
                            </label>
                            <p class="mt-1 text-xs text-slate-400 italic">Widget Turnstile akan muncul di halaman registrasi sebagai tantangan "I'm not a robot" yang ringan dan tidak mengganggu.</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Site Key</label>
                            <input type="text" name="turnstile_site_key" value="{{ $setting->turnstile_site_key }}"
                                class="block w-full rounded-xl border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="0x4AAAAAA...">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-500 mb-2">Secret Key</label>
                            <input type="text" name="turnstile_secret_key" value="{{ $setting->turnstile_secret_key }}"
                                class="block w-full rounded-xl border-slate-300 dark:bg-slate-700 dark:border-slate-600 dark:text-white focus:ring-primary-500 focus:border-primary-500 transition-all"
                                placeholder="0x4AAAAAA...">
                        </div>
                        <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-check-circle text-green-600 dark:text-green-400 mt-0.5"></i>
                                <div class="text-sm text-green-700 dark:text-green-300">
                                    <p class="font-medium mb-1">Perlindungan Multi-Layer aktif:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        <li><strong>Cloudflare Turnstile</strong> — Widget keamanan non-intrusif pengganti reCAPTCHA</li>
                                        <li><strong>Honeypot Field</strong> — Field tersembunyi yang hanya diisi oleh bot</li>
                                        <li><strong>Time Check</strong> — Mencegah form yang dikirim terlalu cepat (< 3 detik)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <p class="font-medium mb-1">Cara mendapatkan Cloudflare Turnstile keys:</p>
                                    <ol class="list-decimal list-inside space-y-1">
                                        <li>Kunjungi <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank" class="underline font-medium">Cloudflare Turnstile Dashboard</a></li>
                                        <li>Klik <strong>Add Widget</strong> dan pilih widget type (disarankan Non-Interactive)</li>
                                        <li>Daftarkan domain Anda (misal: <code class="bg-blue-100 dark:bg-blue-800 px-1 rounded">{{ request()->getHost() }}</code>)</li>
                                        <li>Salin Site Key dan Secret Key ke form di atas</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-bold rounded-xl shadow-sm text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection