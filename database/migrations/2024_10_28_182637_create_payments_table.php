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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sell_id'); // Campo da chave estrangeira
            $table->timestamps();
            $table->string('pagamento');
            $table->integer('parcelas')->nullable();
            $table->float('preco');
            $table->float('troco')->nullable();
            // Definindo a chave estrangeira
            $table->foreign('sell_id')
                  ->references('id')
                  ->on('sells'); // Remove o pagamento se o report_sell for deletado
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};