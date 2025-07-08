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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id')->unique();
            $table->text('issue_description');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('cascade');
            $table->string('status')->default('Open');
            $table->text('acknowledgment')->nullable();
            $table->text('guidance')->nullable();
            $table->text('clarification')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
