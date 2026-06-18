<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->renameColumn('recaptcha_site_key', 'turnstile_site_key');
            $table->renameColumn('recaptcha_secret_key', 'turnstile_secret_key');
            $table->renameColumn('recaptcha_enabled', 'turnstile_enabled');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn('recaptcha_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->renameColumn('turnstile_site_key', 'recaptcha_site_key');
            $table->renameColumn('turnstile_secret_key', 'recaptcha_secret_key');
            $table->renameColumn('turnstile_enabled', 'recaptcha_enabled');
        });

        Schema::table('site_settings', function (Blueprint $table) {
            $table->decimal('recaptcha_threshold', 3, 2)->default(0.5);
        });
    }
};
