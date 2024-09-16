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
        Schema::create('projects', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('name'); // Nome do projeto
            $table->text('description')->nullable(); // Descrição do projeto
            $table->string('videos')->nullable(); // URL ou caminho dos vídeos relacionados ao projeto
            $table->json('images')->nullable(); // Imagens relacionadas ao projeto (JSON)
            $table->string('demo'); // Token da demo
            $table->string('demo_location')->nullable(); // Localização ou URL da demo
            $table->string('github')->nullable(); // URL do repositório no GitHub
            $table->json('skills'); // Habilidades necessárias para o projeto (JSON)
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
        Schema::dropIfExists('projects');
    }
};
