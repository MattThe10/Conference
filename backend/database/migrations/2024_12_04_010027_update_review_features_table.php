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
        Schema::table('review_features', function (Blueprint $table) {
            $table->tinyInteger('rating_type')
                ->comment('0 = yes/no, 1 = 1...6')
                ->after('content')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('review_features', function (Blueprint $table) {
            $table->dropColumn('rating_type');
        });
    }
};
