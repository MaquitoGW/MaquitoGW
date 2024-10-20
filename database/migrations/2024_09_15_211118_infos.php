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
        Schema::create('infos', function (Blueprint $table) {
            $table->id(); // Cria a coluna 'id' como chave primária e autoincremento
            $table->string('avatar')->nullable(); // Coluna para armazenar URL ou caminho da imagem de avatar
            $table->string('language'); // Coluna para nome
            $table->string('name'); // Coluna para nome
            $table->string('fullname'); // Coluna para nome completo
            $table->text('description')->nullable(); // Coluna para descrição
            $table->timestamps(); // Colunas 'created_at' e 'updated_at'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infos');
    }
};
