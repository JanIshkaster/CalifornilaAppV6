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
        Schema::create('ticket_additional_fees', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id', 50);
            $table->decimal('amount', 8, 2);
            $table->string('fee_data_details', 100)->nullable();
            $table->timestamps();
        
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_additional_fees');
    }
};
