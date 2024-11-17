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
        Schema::create('reviews', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('comment', 1000);
            $table->string('pro', 1000)->nullable();
            $table->string('con', 1000)->nullable();
            $table->integer('users_id')->index('fk_reviews_users_idx');
            $table->integer('articles_id')->index('fk_reviews_articles_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
