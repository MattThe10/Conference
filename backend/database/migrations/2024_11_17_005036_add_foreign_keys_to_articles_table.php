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
        Schema::table('articles', function (Blueprint $table) {
            $table->foreign(['article_statuses_id'], 'fk_articles_article_statuses')->references(['id'])->on('article_statuses')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['conferences_id'], 'fk_articles_conferences')->references(['id'])->on('conferences')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign('fk_articles_article_statuses');
            $table->dropForeign('fk_articles_conferences');
        });
    }
};
