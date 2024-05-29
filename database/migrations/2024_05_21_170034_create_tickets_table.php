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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Add this line
            $table->string('ticket_id', 50)->unique();
            $table->integer('steps')->default(1); // Set default value to Step 1
            $table->string('shipping_method')->nullable();
            $table->string('status')->nullable();
            $table->string('tracking')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
