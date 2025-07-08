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

            $table->string('page_number')->nullable()->after('chapter_doi');
            $table->json('collaborations')->nullable()->after('page_number');
            $table->string('percentile')->nullable()->after('collaborations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('book_chapters', function (Blueprint $table) {
            $table->dropColumn(['page_number', 'collaborations', 'percentile']);
        });
    }
};
