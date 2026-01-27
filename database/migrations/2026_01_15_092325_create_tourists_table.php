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
        Schema::create('tourists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->references('id')->on('users');
            $table->string('passport_series');
            $table->string('passport_number');
            $table->string('passport_date');
            $table->string('passport_org');
            $table->foreignId('passport_country_id')->constrained('countries')->references('id')->on('countries');
            $table->date('birth_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tourists');
    }
};
