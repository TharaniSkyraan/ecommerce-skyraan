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
        Schema::create('product_stock_update_quantity_histories', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('history_id'); 
            $table->unsignedBigInteger('warehouse_id'); 
            $table->unsignedBigInteger('product_id'); 
            $table->unsignedBigInteger('product_variant_id'); 
            $table->bigInteger('available_quantity')->default(0);
            $table->bigInteger('upload_quantity')->default(0);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_stock_update_quantity_histories');
    }
};
