<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Inserir cupons inteligentes de exemplo
        DB::table('coupons')->insert([
            // Cupom manual tradicional
            [
                'code' => 'WELCOME10',
                'discount' => 10,
                'type' => 'percent',
                'expires_at' => date('Y-m-d', strtotime('+30 days')),
                'trigger_type' => 'manual',
                'trigger_conditions' => null,
                'user_id' => null,
                'max_uses' => 100,
                'used_count' => 0,
                'minimum_cart_value' => 30.00,
                'applicable_genres' => null,
                'is_active' => true,
                'is_auto_generated' => false,
                'generated_at' => null,
                'last_used_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Cupom de gênero específico (Ficção)
            [
                'code' => 'FICCAO15',
                'discount' => 15,
                'type' => 'percent',
                'expires_at' => date('Y-m-d', strtotime('+15 days')),
                'trigger_type' => 'genre_based',
                'trigger_conditions' => json_encode(['preferred_genre' => 'ficção']),
                'user_id' => null,
                'max_uses' => 50,
                'used_count' => 0,
                'minimum_cart_value' => 40.00,
                'applicable_genres' => json_encode(['ficção', 'ficção científica']),
                'is_active' => true,
                'is_auto_generated' => false,
                'generated_at' => now(),
                'last_used_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Cupom de carrinho alto valor
            [
                'code' => 'VIP-COMP50-25',
                'discount' => 50,
                'type' => 'fixed',
                'expires_at' => date('Y-m-d', strtotime('+24 hours')),
                'trigger_type' => 'high_value_cart',
                'trigger_conditions' => json_encode(['minimum_value' => 200]),
                'user_id' => null,
                'max_uses' => 10,
                'used_count' => 0,
                'minimum_cart_value' => 200.00,
                'applicable_genres' => null,
                'is_active' => true,
                'is_auto_generated' => true,
                'generated_at' => now(),
                'last_used_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('coupons')->whereIn('code', ['WELCOME10', 'FICCAO15', 'VIP-COMP50-25'])->delete();
    }
};