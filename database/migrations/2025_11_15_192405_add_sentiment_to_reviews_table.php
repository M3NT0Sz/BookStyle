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
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('sentiment', ['POSITIVO', 'NEGATIVO', 'NEUTRO'])->default('NEUTRO')->after('comment');
            $table->decimal('sentiment_score', 5, 2)->default(0)->after('sentiment');
            $table->decimal('sentiment_confidence', 5, 2)->default(0)->after('sentiment_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['sentiment', 'sentiment_score', 'sentiment_confidence']);
        });
    }
};
