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
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('flight_number')->nullable();
            $table->string('flight_price')->nullable();
            $table->string('flight_origin')->nullable();
            $table->string('flight_destination')->nullable();
            $table->string('flight_airline')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('flight_number');
            $table->dropColumn('flight_price');
            $table->dropColumn('flight_origin');
            $table->dropColumn('flight_destination');
            $table->dropColumn('flight_airline');
        });
    }
};
