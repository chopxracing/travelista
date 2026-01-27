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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('stars')->nullable();
            $table->foreignId('country_id')->constrained('countries')->references('id')->on('countries');
            $table->foreignId('city_id')->constrained('cities')->references('id')->on('cities');
            $table->integer('meters_to_sea')->nullable();
            $table->integer('meters_to_center')->nullable();
            $table->string('address');
            $table->text('description')->nullable();
            $table->string('check_in_time');
            $table->string('check_out_time');
            $table->boolean('is_active');
            $table->string('preview_image')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
