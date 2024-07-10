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
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->unsignedBigInteger('stock_card_id')->nullable()->after('product_id');

            // If you have a StockProductsCard model and want to enforce foreign key constraints
            $table->foreign('stock_card_id')->references('id')->on('stock_products_cards')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropForeign(['stock_card_id']);
            $table->dropColumn('stock_card_id');
        });
    }
};
