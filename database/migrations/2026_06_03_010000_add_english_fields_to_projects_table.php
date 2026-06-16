<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('name_en')->nullable()->after('name');
            $table->text('preview_en')->nullable()->after('preview');
            $table->longText('description_en')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn(['name_en', 'preview_en', 'description_en']);
        });
    }
};
