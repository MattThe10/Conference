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
        Schema::table('users_has_articles', function (Blueprint $table) {
            $table->foreign(['articles_id'], 'fk_users_has_articles_articles')->references(['id'])->on('articles')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['users_id'], 'fk_users_has_articles_users')->references(['id'])->on('users')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_has_articles', function (Blueprint $table) {
            $table->dropForeign('fk_users_has_articles_articles');
            $table->dropForeign('fk_users_has_articles_users');
        });
    }
};
