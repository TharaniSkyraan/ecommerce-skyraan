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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('product_id')->default(0);
            $table->string('sku', 30)->notNullable();
            $table->double('price', 16, 2)->notNullable();
            $table->double('sale_price', 16, 2)->default(0);
            $table->double('cost_per_item', 16, 2)->default(0);
            $table->double('search_price', 16, 2)->default(0);
            $table->bigInteger('available_quantity')->default(0);
            $table->double('shipping_wide', 16, 2)->default(0);
            $table->double('shipping_length', 16, 2)->default(0);
            $table->double('shipping_height', 16, 2)->default(0);
            $table->double('shipping_weight', 16, 2)->default(0);
            $table->dateTime('discount_start_date')->nullable();
            $table->dateTime('discount_end_date')->nullable();
            $table->enum('discount_duration', ['yes', 'no'])->default('no');
            $table->longText('images');
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->default('in_stock');
            $table->enum('is_default', ['yes', 'no'])->default('no');
            $table->softDeletes();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
