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
        Schema::create('patents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('investors_name');
            $table->string('work_title');
            $table->text('work_description');
            $table->unsignedSmallInteger('year');
            $table->enum('type', ['filed', 'published', 'granted'])->default('filed');
            $table->string('publication_number')->nullable();
            $table->string('grant_number')->nullable();
            $table->string('certificate_path')->nullable();
            $table->foreignId('research_paper_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patents');
    }
};
