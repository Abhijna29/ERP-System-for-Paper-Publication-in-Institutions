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
        Schema::table('research_papers', function (Blueprint $table) {
            $table->string('indexing_database')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('source')->nullable();
            $table->string('volume_number')->nullable();
            $table->string('issue_number')->nullable();
            $table->string('page_number')->nullable();
            $table->string('doi')->nullable();
            $table->json('collaborations')->nullable();
            $table->string('percentile')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_papers', function (Blueprint $table) {
            $table->dropColumn(['indexing_database', 'source', 'volume_number', 'issue_number', 'page_number', 'publication_date', 'doi', 'collaborations', 'percentile']);
        });
    }
};
