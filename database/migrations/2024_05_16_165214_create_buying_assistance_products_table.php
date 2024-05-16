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
        Schema::create('buying_assistance_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Add this line
            $table->string('product_name');
            $table->string('product_link');
            $table->Integer('product_qty');
            $table->string('product_variant')->nullable();
            $table->string('shipping_method'); 
            $table->string('request_method'); 
            $table->timestamps();
    
            // Add this line to set up the foreign key constraint
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buying_assistance_products');
    }
};
