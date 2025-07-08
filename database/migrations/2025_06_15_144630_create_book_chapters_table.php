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
        Schema::create('book_chapters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->string('chapter_title');
            $table->text('keywords')->nullable();
            $table->text('genre')->nullable();
            $table->enum('status', ['submitted', 'under_review', 'approved', 'rejected', 'pending_payment', 'revision_required', 'ready_to_publish', 'published', 'resubmitted'])->default('submitted');
            $table->date('chapter_publication_date')->nullable();
            $table->string('resubmission_count')->default(0);
            $table->string('chapter_doi')->nullable();
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('book_chapters', function (Blueprint $table) {
            $table->dropForeign(['book_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('book_chapters');
    }
};
