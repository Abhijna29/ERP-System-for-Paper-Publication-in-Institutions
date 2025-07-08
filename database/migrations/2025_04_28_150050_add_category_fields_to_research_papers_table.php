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
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->nullOnDelete();
            $table->foreignId('child_category_id')->nullable()->constrained('child_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_papers', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['sub_category_id']);
            $table->dropForeign(['child_category_id']);
            $table->dropColumn(['category_id', 'sub_category_id', 'child_category_id']);
        });
    }
};
