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
        Schema::table('book_chapter_reviews', function (Blueprint $table) {
            $table->date('deadline')->nullable()->after('status');
            $table->date('last_notified_at')->nullable()->after('deadline');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_chapter_reviews', function (Blueprint $table) {
            $table->dropColumn('deadline');
            $table->dropColumn('last_notified_at');
        });
    }
};
