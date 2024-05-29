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
            $table->integer('handling_fee')->nullable();
            $table->integer('custom_tax')->nullable();
            $table->integer('convenience_fee')->nullable();
            $table->integer('credit_card_fee')->nullable();
            $table->integer('dollar_conversion')->nullable();
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
