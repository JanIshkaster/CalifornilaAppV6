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
        Schema::create('customer_added_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('warehouse_status');
            $table->integer('quantity');
            $table->string('order_number');
            $table->string('merchant');
            $table->string('package_type');
            $table->decimal('value', 8, 2);
            $table->text('description');
            $table->string('status');
            $table->text('note');
            $table->string('product_image')->nullable();
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_added_products');
    }
};
