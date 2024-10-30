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
        Schema::create('sells', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->unsignedBigInteger('user_id'); // Chave estrangeira para 'users.id'
            $table->boolean('cancelado')->default(1);
            $table->float('preco_total');
            $table->timestamps(); // Adiciona 'created_at' e 'updated_at'

            $table->foreign('user_id')
            ->references('id')
            ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sells');
    }
};