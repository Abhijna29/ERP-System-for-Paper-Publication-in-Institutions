<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('child_categories', function (Blueprint $table) {
            $table->unique(['name', 'category_id', 'sub_category_id']);
        });
    }

    public function down()
    {
        Schema::table('child_categories', function (Blueprint $table) {
            $table->unique('name');
        });
    }
};
