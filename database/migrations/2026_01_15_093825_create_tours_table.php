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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_operator_id')->constrained('tour_operators')->references('id')->on('tour_operators');
            $table->string('name');
            $table->text('description');
            $table->foreignId('tour_type_id')->constrained('tour_types')->references('id')->on('tour_types');
            $table->foreignId('city_id')->constrained('cities')->references('id')->on('cities');
            $table->foreignId('country_id')->constrained('countries')->references('id')->on('countries');
            $table->float('price');
            $table->integer('days');
            $table->foreignId('hotel_id')->constrained('hotels')->references('id')->on('hotels');
            $table->date('date_from');
            $table->date('date_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
