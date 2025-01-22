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
        Schema::create('users_has_articles', function (Blueprint $table) {
            $table->integer('id', true);
            $table->integer('users_id')->index('fk_users_has_articles_users_idx');
            $table->integer('articles_id')->index('fk_users_has_articles_articles_idx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_has_articles');
    }
};
