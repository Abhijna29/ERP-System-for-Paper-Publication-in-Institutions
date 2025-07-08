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
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->integer('amount')->after('ends_at')->default(0);
            $table->integer('papers_used')->after('amount')->default(0);
            $table->integer('downloads_used')->after('papers_used')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('amount');
            $table->dropColumn('papers_used');
            $table->dropColumn('downloads_used');
        });
    }
};
