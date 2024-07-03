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
        Schema::create('stock_products_cards', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->float('quantity');
            $table->float('stock_before');
            $table->float('stock_after');
            $table->string('uom', 15)->nullable();
            $table->longText('description')->nullable();
            $table->bigInteger('stock_id')->nullable();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->boolean('is_executed')->nullable()->default(false);
            $table->longText('exec_info')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_items_cards');
    }
};
