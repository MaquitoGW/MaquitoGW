<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('app_settings')) {
            Schema::create('app_settings', function (Blueprint $table) {
                $table->id();
                $table->string('key', 150)->unique();
                $table->longText('value')->nullable();
                $table->string('type')->default('string');
                $table->timestamps();
            });
        }

        $now = now();
        $defaults = [
            ['key' => 'APP_TITLE', 'value' => env('APP_TITLE'), 'type' => 'string'],
            ['key' => 'APP_NAME', 'value' => env('APP_NAME'), 'type' => 'string'],
            ['key' => 'APP_ENV', 'value' => env('APP_ENV', 'production'), 'type' => 'string'],
            ['key' => 'APP_DEBUG', 'value' => env('APP_DEBUG', false) ? 'true' : 'false', 'type' => 'boolean'],
            ['key' => 'APP_TIMEZONE', 'value' => env('APP_TIMEZONE', 'UTC'), 'type' => 'string'],
            ['key' => 'APP_FAKER_LOCALE', 'value' => env('APP_FAKER_LOCALE', 'en_US'), 'type' => 'string'],
            ['key' => 'MULTIPLE_LANGUAGES', 'value' => env('MULTIPLE_LANGUAGES', 0), 'type' => 'boolean'],
            ['key' => 'PROJECT_TRANSLATION_ENABLED', 'value' => env('PROJECT_TRANSLATION_ENABLED', false) ? 'true' : 'false', 'type' => 'boolean'],
            ['key' => 'PROJECT_TRANSLATION_PROVIDER', 'value' => env('PROJECT_TRANSLATION_PROVIDER', 'google'), 'type' => 'string'],
            ['key' => 'GOOGLE_TRANSLATE_URL', 'value' => env('GOOGLE_TRANSLATE_URL', 'https://translate.googleapis.com/translate_a/single'), 'type' => 'string'],
            ['key' => 'PORTFOLIO_ENABLED', 'value' => env('PORTFOLIO_ENABLED', 'true'), 'type' => 'boolean'],
            ['key' => 'FILE_STORAGE_DRIVER', 'value' => env('FILE_STORAGE_DRIVER', 'local'), 'type' => 'string'],
            ['key' => 'R2_ENDPOINT', 'value' => env('R2_ENDPOINT'), 'type' => 'string'],
            ['key' => 'R2_ACCESS_KEY_ID', 'value' => env('R2_ACCESS_KEY_ID'), 'type' => 'string'],
            ['key' => 'R2_SECRET_ACCESS_KEY', 'value' => env('R2_SECRET_ACCESS_KEY'), 'type' => 'string'],
            ['key' => 'R2_BUCKET', 'value' => env('R2_BUCKET'), 'type' => 'string'],
            ['key' => 'R2_PUBLIC_URL', 'value' => env('R2_PUBLIC_URL'), 'type' => 'string'],
            ['key' => 'R2_DEFAULT_REGION', 'value' => env('R2_DEFAULT_REGION', 'auto'), 'type' => 'string'],
        ];

        foreach ($defaults as $item) {
            DB::table('app_settings')->updateOrInsert(
                ['key' => $item['key']],
                $item + [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
