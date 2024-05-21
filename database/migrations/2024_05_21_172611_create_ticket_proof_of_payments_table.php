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
        Schema::create('ticket_proof_of_payments', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_id', 50);
            $table->string('image_path');
            $table->timestamps();
        
            $table->foreign('ticket_id')->references('ticket_id')->on('tickets')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_proof_of_payments');
    }
};
