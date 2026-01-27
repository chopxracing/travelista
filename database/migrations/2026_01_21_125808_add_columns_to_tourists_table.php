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
            $table->string('name')->nullable();
            $table->string('surname')->nullable();
            $table->string('last_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourists', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('surname');
            $table->dropColumn('last_name');
        });
    }
};
