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
        Schema::table('users', function (Blueprint $table) {
            $table->foreign(['faculties_id'], 'fk_users_faculties')->references(['id'])->on('faculties')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['roles_id'], 'fk_users_roles')->references(['id'])->on('roles')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('fk_users_faculties');
            $table->dropForeign('fk_users_roles');
        });
    }
};
