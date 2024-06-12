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
        Schema::create('webhook_data', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('ticket_id', 100); // Increase the size if necessary
            $table->string('shopify_order_id', 100); 
            $table->string('shopify_product_id', 100); 
            $table->string('payment_type', 100); 
            $table->text('data');
            $table->timestamps();

            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('webhook_data');
    }
};
