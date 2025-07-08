<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE research_papers MODIFY COLUMN status ENUM('submitted', 'under_review', 'resubmitted', 'approved', 'rejected', 'revision_required', 'pending_payment','published', 'ready_to_publish') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE research_papers MODIFY COLUMN status ENUM('submitted', 'under_review', 'resubmitted', 'approved', 'rejected', 'revision_required', 'published') NOT NULL");
    }
};
