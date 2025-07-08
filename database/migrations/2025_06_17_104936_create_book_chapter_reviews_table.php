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
        Schema::create('book_chapter_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_chapter_id')->constrained()->onDelete('cascade');
            $table->foreignId('reviewer_id')->constrained('users')->onDelete('cascade');
            $table->text('comments')->nullable();
            $table->integer('rating')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'revision_required', 'resubmitted'])->default('pending');
            $table->date('deadline')->nullable();
            $table->date('last_notified_at')->nullable();
            $table->boolean('flagged_for_editor')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_chapter_reviews');
    }
};
