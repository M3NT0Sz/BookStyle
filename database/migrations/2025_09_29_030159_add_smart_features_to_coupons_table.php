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
        Schema::table('coupons', function (Blueprint $table) {
            // Recursos de cupons inteligentes
            $table->enum('trigger_type', [
                'manual',           // Cupom manual (padrão atual)
                'first_purchase',   // Primeiro pedido
                'cart_abandonment', // Abandono de carrinho
                'birthday',         // Aniversário do usuário
                'genre_based',      // Baseado em gênero preferido
                'loyalty',          // Fidelidade (X pedidos)
                'high_value_cart'   // Carrinho de alto valor
            ])->default('manual')->after('type');
            
            // Configurações específicas do trigger
            $table->json('trigger_conditions')->nullable()->after('trigger_type');
            
            // Usuário específico (null = válido para todos)
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade')->after('trigger_conditions');
            
            // Limitações de uso
            $table->integer('max_uses')->nullable()->after('user_id');
            $table->integer('used_count')->default(0)->after('max_uses');
            
            // Valor mínimo do carrinho para aplicar
            $table->decimal('minimum_cart_value', 10, 2)->nullable()->after('used_count');
            
            // Gêneros específicos (JSON array)
            $table->json('applicable_genres')->nullable()->after('minimum_cart_value');
            
            // Status do cupom
            $table->boolean('is_active')->default(true)->after('applicable_genres');
            $table->boolean('is_auto_generated')->default(false)->after('is_active');
            
            // Datas de geração automática
            $table->timestamp('generated_at')->nullable()->after('is_auto_generated');
            $table->timestamp('last_used_at')->nullable()->after('generated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn([
                'trigger_type',
                'trigger_conditions',
                'user_id',
                'max_uses',
                'used_count',
                'minimum_cart_value',
                'applicable_genres',
                'is_active',
                'is_auto_generated',
                'generated_at',
                'last_used_at'
            ]);
        });
    }
};
