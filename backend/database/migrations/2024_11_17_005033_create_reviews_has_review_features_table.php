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
        Schema::create('reviews_has_review_features', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('reviews_id')->index('fk_reviews_has_review_features_reviews_idx');
            $table->integer('review_features_id')->index('fk_reviews_has_review_features_review_features_idx');
            $table->integer('rating')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews_has_review_features');
    }
};
