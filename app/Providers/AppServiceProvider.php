<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use App\Models\Company;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (!app()->runningInConsole()) {
            // LOGIKA FAVICON GLOBAL — cached for 60 minutes
            $companyData = Cache::remember('global_company_data', 3600, function () {
                if (!Schema::hasTable('companies')) {
                    return ['favicon' => asset('favicon.ico'), 'company' => null];
                }

                $company = null;

                if (Schema::hasColumn('companies', 'admin_id')) {
                    $company = Company::whereHas('admin', function ($q) {
                        $q->where('role', 'superadmin');
                    })->first();
                }

                if (!$company) {
                    $company = Company::first();
                }

                $faviconUrl = ($company && $company->logo_path)
                    ? asset('uploads/' . $company->logo_path)
                    : asset('favicon.ico');

                return ['favicon' => $faviconUrl, 'company' => $company];
            });

            View::share('global_favicon', $companyData['favicon']);
            View::share('company', $companyData['company']);

            // Force HTTPS or HTTP based on SiteSetting — cached for 60 minutes
            $connectionMode = Cache::remember('site_connection_mode', 3600, function () {
                if (!Schema::hasTable('site_settings')) {
                    return null;
                }
                $setting = SiteSetting::first();
                return $setting ? $setting->connection_mode : null;
            });

            if ($connectionMode === 'https') {
                URL::forceScheme('https');
            } elseif ($connectionMode === 'http') {
                URL::forceScheme('http');
            }
        }

        // Register Login Listener
        Event::listen(
            Login::class,
            LogSuccessfulLogin::class
        );
    }
}