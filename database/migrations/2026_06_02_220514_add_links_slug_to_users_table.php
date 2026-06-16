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
        Schema::table('users', function (Blueprint $table) {
            // Remove coluna se existir
            if (Schema::hasColumn('users', 'links_slug')) {
                $table->dropColumn('links_slug');
            }
            // Adicionar a coluna
            $table->string('links_slug', 50)->default('links')->unique();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'links_slug')) {
                $table->dropColumn('links_slug');
            }
        });
    }
};
