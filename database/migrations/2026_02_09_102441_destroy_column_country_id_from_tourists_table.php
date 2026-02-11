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
        Schema::table('tourists', function (Blueprint $table) {
            $table->dropForeign('tourists_passport_country_id_foreign');
            $table->dropColumn('passport_country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourists', function (Blueprint $table) {
            $table->foreign('passport_country_id')->references('country_id')->on('countries');
        });
    }
};
