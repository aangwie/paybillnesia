<?php

namespace App\Http\Controllers;

use App\Models\RouterSetting;
use App\Services\MikrotikService;
use Illuminate\Http\Request;
use App\Models\Plan;

class RouterSettingController extends Controller
{
    /**
     * Check if the authenticated user owns the given router.
     * Superadmin also owns routers with null admin_id (legacy data).
     */
    private function isRouterOwner($router)
    {
        $user = auth()->user();
        return $router->admin_id == $user->id || ($user->isSuperAdmin() && $router->admin_id === null);
    }
    public function index(Request $request)
    {
        $ownership = $request->input('ownership', 'semua');
        $query = RouterSetting::orderBy('is_active', 'desc');

        // Jika superadmin, beri opsi filter kepemilikan
        if (auth()->user()->isSuperAdmin()) {
            if ($ownership === 'superadmin') {
                $query->whereHas('admin', function ($q) {
                    $q->where('role', 'superadmin');
                });
            } elseif ($ownership === 'admin') {
                $query->whereHas('admin', function ($q) {
                    $q->where('role', 'admin')->orWhere('role', 'operator');
                });
            }
        }
        // Admin/tenant: TenantScope already filters by admin_id

        $routers = $query->with('admin')->get();

        $plans = Plan::all();
        return view('router.index', compact('routers', 'plans', 'ownership'));
    }

    /**
     * AJAX endpoint: check MikroTik connection status for a single router.
     * Returns JSON: { connected: bool, identity: string|null }
     */
    public function checkConnection($id)
    {
        $router = RouterSetting::withoutGlobalScopes()->find($id);

        if (!$router) {
            return response()->json(['connected' => false, 'identity' => null]);
        }

        $status = MikrotikService::checkRouterConnection(
            $router->host,
            $router->username,
            $router->password,
            $router->port
        );

        return response()->json($status);
    }

    // SIMPAN BARU / UPDATE
    public function store(Request $request)
    {
        $user = auth()->user();

        // Ownership guard: if editing, verify ownership
        if ($request->id) {
            $existing = RouterSetting::withoutGlobalScopes()->find($request->id);
            if (!$existing || !$this->isRouterOwner($existing)) {
                return back()->with('error', 'Anda tidak memiliki izin untuk mengedit router ini.');
            }
        }

        // Superadmin bypasses plan and activation checks
        if (!$user->isSuperAdmin()) {
            $plan = $user->plan;
            if (!$plan) {
                return back()->with('error', 'Silakan hubungi Superadmin untuk aktivasi paket layanan Anda.');
            }

            if (!$request->id) { // Jika INSERT baru
                $currentCount = RouterSetting::where('admin_id', $user->id)->count();
                if ($currentCount >= $plan->max_routers) {
                    return back()->with('error', "Limit Router Tercapai! Paket Anda (" . $plan->name . ") hanya mendukung maksimal " . $plan->max_routers . " router.");
                }
            }

            // Restriction: Only activated users can add routers
            if (!$request->id && !$user->is_activated) {
                return back()->with('error', 'Akun Admin Anda belum diaktifkan oleh Superadmin untuk menambah router.');
            }
        }

        $data = [
            'label' => $request->label,
            'host' => $request->host,
            'username' => $request->username,
            'port' => $request->port,
        ];

        // Jika password diisi, update. Jika kosong, biarkan password lama (khusus edit).
        if ($request->filled('password')) {
            $data['password'] = $request->password;
        }

        // Cek ID (jika ada ID berarti Edit, jika tidak berarti Baru)
        if ($request->id) {
            $router = RouterSetting::withoutGlobalScopes()->find($request->id);
            $router->update($data);
            $msg = 'Konfigurasi berhasil diperbarui.';
        } else {
            // Jika ini router pertama milik user, langsung set aktif
            if (RouterSetting::where('admin_id', $user->id)->count() == 0) {
                $data['is_active'] = true;
            }
            $data['password'] = $request->password; // Password wajib buat baru
            RouterSetting::create($data);
            $msg = 'Router baru berhasil ditambahkan.';
        }

        return back()->with('success', $msg);
    }

    // AKTIFKAN ROUTER (GUNAKAN)
    public function activate($id)
    {
        $user = auth()->user();
        $router = RouterSetting::withoutGlobalScopes()->find($id);

        if (!$router || !$this->isRouterOwner($router)) {
            return back()->with('error', 'Anda tidak memiliki izin untuk mengaktifkan router ini.');
        }

        // Matikan semua router milik user ini saja
        RouterSetting::withoutGlobalScopes()
            ->where('admin_id', $user->id)
            ->update(['is_active' => false]);

        // Aktifkan yang dipilih
        $router->update(['is_active' => true]);

        return back()->with('success', "Berhasil beralih ke router: {$router->label} ({$router->host})");
    }

    // HAPUS ROUTER
    public function destroy($id)
    {
        $user = auth()->user();
        $router = RouterSetting::withoutGlobalScopes()->find($id);

        if (!$router || !$this->isRouterOwner($router)) {
            return back()->with('error', 'Anda tidak memiliki izin untuk menghapus router ini.');
        }

        if ($router->is_active) {
            return back()->with('error', 'Tidak bisa menghapus router yang sedang digunakan (Aktif). Pindahkan koneksi dulu.');
        }

        $router->delete();
        return back()->with('success', 'Data konfigurasi router dihapus.');
    }
}