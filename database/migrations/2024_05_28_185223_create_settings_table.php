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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('handling_fee', 8, 2)->nullable();
            $table->decimal('custom_tax', 8, 2)->nullable();
            $table->decimal('convenience_fee', 8, 2)->nullable();
            $table->decimal('credit_card_fee', 8, 2)->nullable();
            $table->decimal('dollar_conversion', 8, 2)->nullable();
            $table->string('admin_emails')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
