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
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->decimal('price_alert', 10, 2)->nullable(); // Preço desejado para alerta
            $table->boolean('notified')->default(false); // Se já foi notificado sobre promoção
            $table->timestamps();
            
            // Impedir duplicatas (usuário não pode adicionar o mesmo livro duas vezes)
            $table->unique(['user_id', 'book_id']);
            
            // Índices para performance
            $table->index('user_id');
            $table->index('book_id');
            $table->index(['price_alert', 'notified']); // Para buscar alertas de preço
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
    }
};
