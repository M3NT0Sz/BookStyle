<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            // Ebook
            $table->string('file_format')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('has_drm')->nullable();

            // BookBox
            $table->integer('books_count')->nullable();
            $table->text('titles')->nullable(); // JSON
            $table->text('extras')->nullable(); // JSON

            // ComicBook
            $table->integer('issue_number')->nullable();
            $table->string('illustrator')->nullable();
            $table->boolean('is_colored')->nullable();

            // PhysicalBook
            $table->integer('pages')->nullable();
            $table->string('cover_type')->nullable();
            $table->float('weight')->nullable();
            $table->string('dimensions')->nullable(); // ou text/json
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropColumn([
                'file_format', 'file_size', 'has_drm',
                'books_count', 'titles', 'extras',
                'issue_number', 'illustrator', 'is_colored',
                'pages', 'cover_type', 'weight', 'dimensions'
            ]);
        });
    }
};
