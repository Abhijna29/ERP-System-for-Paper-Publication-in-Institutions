<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class UpdateReviewStatusEnum extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE reviews MODIFY status ENUM('pending', 'approved', 'rejected', 'revision_required','resubmitted') NOT NULL");
    }

    public function down()
    {
        DB::statement("ALTER TABLE reviews MODIFY status ENUM('pending', 'approved', 'rejected') NOT NULL");
    }
}
