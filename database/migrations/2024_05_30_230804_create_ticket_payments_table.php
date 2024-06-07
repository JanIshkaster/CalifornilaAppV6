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
        Schema::create('ticket_payments', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id', 50);
            $table->decimal('total_handling_fee', 8, 2);
            $table->decimal('total_custom_tax', 8, 2);
            $table->decimal('total_convenience_fee', 8, 2);
            $table->decimal('total_credit_card_fee', 8, 2);
            $table->decimal('total_product_value', 8, 2);
            $table->decimal('total_product_price', 8, 2);
            $table->string('payment_type', 100)->nullable();
            $table->string('image_path')->nullable();
            $table->timestamps();
        
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_payments');
    }
};
