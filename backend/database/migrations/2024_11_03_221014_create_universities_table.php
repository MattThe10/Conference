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
        Schema::create('universities', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('abbreviation')->nullable();
            $table->string('street_address')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->restrictOnDelete();

            $table->timestamps();
        });

        Schema::create('faculties', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('street_address')->nullable();
            $table->string('city');
            $table->string('district')->nullable();
            $table->string('region')->nullable();
            $table->string('postal_code')->nullable();

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->restrictOnDelete();

            $table->unsignedBigInteger('university_id');
            $table->foreign('university_id')->references('id')->on('universities')->restrictOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('universities');
    }
};
