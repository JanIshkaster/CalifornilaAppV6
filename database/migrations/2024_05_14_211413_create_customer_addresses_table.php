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
        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Add this line
            $table->string('street');
            $table->string('region');
            $table->string('province');
            $table->string('barangay');
            $table->string('city');
            $table->string('zipcode');
            $table->date('birthdate');
            $table->string('gender');
            $table->string('contact');
            $table->string('hear');
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
        Schema::dropIfExists('customer_addresses');
    }
};
