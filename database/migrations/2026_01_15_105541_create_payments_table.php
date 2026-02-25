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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->references('id')->on('bookings');
            $table->string('payment_reference')->nullable();
            $table->string('payment_type')->nullable();
            $table->float('amount');
            $table->string('currency')->default('RUB');
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('refunded_at')->nullable();
            $table->float('refunded_amount')->nullable();
            $table->string('refund_reason')->nullable();
            $table->json('payment_details')->nullable();
            $table->string('card_last_four')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
