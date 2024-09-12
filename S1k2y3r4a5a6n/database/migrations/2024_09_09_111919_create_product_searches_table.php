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
        Schema::create('product_searches', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id')->default(0);
            $table->integer('variant_id')->default(0);
            $table->string('product_name',180);
            $table->string('slug',180);
            $table->text('description');
            $table->text('content');
            $table->double('price', 16, 2)->notNullable();
            $table->double('sale_price', 16, 2)->default(0);
            $table->double('discount', 16, 2)->default(0);
            $table->double('search_price', 16, 2)->default(0);
            $table->double('shipping_wide', 16, 2)->default(0);
            $table->double('shipping_length', 16, 2)->default(0);
            $table->double('shipping_height', 16, 2)->default(0);
            $table->double('shipping_weight', 16, 2)->default(0);
            $table->dateTime('discount_start_date')->nullable();
            $table->dateTime('discount_end_date')->nullable();
            $table->longText('images');
            $table->text('image1');
            $table->text('image2');
            $table->bigInteger('cart_limit')->default(0);
            $table->string('label',50);
            $table->string('label_color_code',50);
            $table->dateTime('product_created_at');
            $table->integer('product_type')->default(0);
            $table->string('category_ids',255);
            $table->string('warehouse_ids',255);
            $table->string('attribute_set_ids', 100);
            $table->integer('review')->default(0);
            $table->bigInteger('review_count')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_searches');
    }
};
