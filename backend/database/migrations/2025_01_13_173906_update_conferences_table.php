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
        Schema::table('conferences', function (Blueprint $table) {
            $table->string('title')->default('');
            $table->string('abstract', 1000)->default('');
            $table->dateTime('review_assignment_deadline')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('review_submission_deadline')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('review_publication_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->tinyInteger('is_active')->default(1);
            $table->dropColumn(['start_year', 'end_year']);
        });
    }

    /**
    * Reverse the migrations.
    */
    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn(['title', 'abstract', 'review_assignment_deadline', 'review_submission_deadline', 'review_publication_date', 'is_active']);
            $table->year('start_year');
            $table->year('end_year');
        });
    }
};
