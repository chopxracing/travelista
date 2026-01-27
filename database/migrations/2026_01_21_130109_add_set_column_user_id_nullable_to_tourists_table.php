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
            $table->dropForeign(['user_id']);

            // 2. Делаем столбец nullable
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // 3. Добавляем foreign key обратно
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tourists', function (Blueprint $table) {
            $table->dropForeign(['user_id']);

            // 2. Возвращаем столбец к not nullable
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // 3. Добавляем foreign key обратно
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
};
