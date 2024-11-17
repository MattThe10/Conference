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
        Schema::table('reviews_has_review_features', function (Blueprint $table) {
            $table->foreign(['review_features_id'], 'fk_reviews_has_review_features_review_features')->references(['id'])->on('review_features')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['reviews_id'], 'fk_reviews_has_review_features_reviews')->references(['id'])->on('reviews')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews_has_review_features', function (Blueprint $table) {
            $table->dropForeign('fk_reviews_has_review_features_review_features');
            $table->dropForeign('fk_reviews_has_review_features_reviews');
        });
    }
};
