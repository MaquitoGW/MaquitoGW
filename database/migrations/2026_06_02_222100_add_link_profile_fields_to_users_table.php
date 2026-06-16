<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('links_display_name')->nullable()->after('links_slug');
            $table->string('links_bio', 500)->nullable()->after('links_display_name');
            $table->string('links_avatar')->nullable()->after('links_bio');
            $table->string('links_banner')->nullable()->after('links_avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'links_display_name',
                'links_bio',
                'links_avatar',
                'links_banner',
            ]);
        });
    }
};
