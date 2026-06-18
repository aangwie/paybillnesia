<?php

namespace App\Http\Controllers;

use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $setting = SiteSetting::first();
        if (!$setting) {
            $setting = SiteSetting::create([
                'about_us' => 'Selamat datang di layanan kami.',
                'terms_conditions' => 'Syarat dan ketentuan berlaku.',
                'connection_mode' => 'auto',
                'mobile_api_url' => null,
            ]);
        }
        return view('superadmin.settings.site', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = SiteSetting::first();

        $data = $request->only([
            'about_us', 'terms_conditions', 'connection_mode', 'mobile_api_url',
            'turnstile_site_key', 'turnstile_secret_key',
        ]);
        $data['turnstile_enabled'] = $request->has('turnstile_enabled') ? true : false;

        $setting->update($data);

        return back()->with('success', 'Informasi situs berhasil diperbarui.');
    }
}
