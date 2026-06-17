<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('customizations')) {
            return;
        }

        $now = now();
        $defaults = [
            'theme_mode' => 'system',
            'site_skin' => 'default',
            'color_primary' => '#2f81f7',
            'color_secondary' => '#6e7681',
            'color_text' => '#24292f',
            'color_button' => '#2f81f7',
            'custom_logo_code' => 'MinhaLogo',
            'favicon' => '',
        ];

        foreach ($defaults as $config => $value) {
            DB::table('customizations')->updateOrInsert(
                ['config' => $config],
                [
                    'value' => $value ?? '',
                    'encode' => false,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    public function down(): void
    {
        //
    }
};
