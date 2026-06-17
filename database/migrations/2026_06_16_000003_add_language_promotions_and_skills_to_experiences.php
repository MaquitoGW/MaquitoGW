<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->string('position_en')->nullable()->after('position');
            $table->text('description_en')->nullable()->after('description');
            $table->json('promotions')->nullable()->after('description_en');
            $table->json('promotions_en')->nullable()->after('promotions');
            $table->json('skills')->nullable()->after('promotions_en');
        });
    }

    public function down(): void
    {
        Schema::table('experiences', function (Blueprint $table) {
            $table->dropColumn(['position_en', 'description_en', 'promotions', 'promotions_en', 'skills']);
        });
    }
};
