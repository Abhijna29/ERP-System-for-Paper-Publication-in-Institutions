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
        Schema::table('book_chapters', function (Blueprint $table) {
            $table->json('collaborations')->nullable()->after('chapter_doi');
            $table->string('page_number')->nullable()->after('collaborations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_chapters', function (Blueprint $table) {
            $table->dropColumn('collaborations');
            $table->dropColumn('page_number');
        });
    }
};
