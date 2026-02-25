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
            $table->foreignId('room_type_id')->nullable()->constrained('room_types');
            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign('bookings_room_type_id_foreign');
            $table->dropColumn('room_type_id');
            $table->dropColumn('date_from');
            $table->dropColumn('date_to');
        });
    }
};
