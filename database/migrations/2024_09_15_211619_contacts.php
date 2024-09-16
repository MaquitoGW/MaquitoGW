<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('instagram')->nullable(); // Conta do Instagram
            $table->string('twitter')->nullable(); // Conta do Twitter
            $table->string('linkedin')->nullable(); // Conta do LinkedIn
            $table->string('github')->nullable(); // Conta do GitHub
            $table->string('email_personal'); // Email pessoal
            $table->string('email_business')->nullable(); // Email empresarial
            $table->string('tel')->nullable(); // Número de telefone
            $table->timestamps(); // Cria as colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
};
