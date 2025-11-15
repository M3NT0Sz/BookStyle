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
        Schema::table('orders', function (Blueprint $table) {
            // Renomear total para total_amount se não existir
            if (!Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total', 'total_amount');
            }
            
            // Adicionar campos de endereço como strings individuais
            if (!Schema::hasColumn('orders', 'shipping_city')) {
                $table->string('shipping_city')->nullable()->after('shipping_address');
            }
            if (!Schema::hasColumn('orders', 'shipping_zip')) {
                $table->string('shipping_zip')->nullable()->after('shipping_city');
            }
            
            // Adicionar campo de desconto se não existir
            if (!Schema::hasColumn('orders', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0)->after('total_amount');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'discount_amount')) {
                $table->dropColumn('discount_amount');
            }
            if (Schema::hasColumn('orders', 'shipping_zip')) {
                $table->dropColumn('shipping_zip');
            }
            if (Schema::hasColumn('orders', 'shipping_city')) {
                $table->dropColumn('shipping_city');
            }
            if (Schema::hasColumn('orders', 'total_amount')) {
                $table->renameColumn('total_amount', 'total');
            }
        });
    }
};
