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
        Schema::create('order_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->default(0);
            $table->integer('product_id')->default(0);
            $table->double('quantity', 16, 2);
            $table->double('price', 16, 2)->default(0); // price without tax
            $table->double('sale_price', 16, 2)->default(0); // sale_price without tax
            $table->integer('tax_id')->nullable();
            $table->string('tax', 30)->nullable();
            $table->double('tax_amount', 16, 2)->default(0);
            $table->double('taxable_amount', 16, 2)->default(0); // (price*qty)
            $table->double('gross_amount', 16, 2)->default(0); // tax_amount + (price*qty)
            $table->double('discount_amount', 16, 2)->default(0); 
            $table->double('sub_total', 16, 2)->default(0); // gross_amount - discount_amount
            
            $table->double('shipping_charge', 16, 2)->default(0);
            $table->integer('shipping_tax_id')->nullable();
            $table->string('shipping_tax', 30)->nullable();
            $table->double('shipping_tax_amount', 16, 2)->default(0);
            $table->double('shipping_taxable_amount', 16, 2)->default(0);
            $table->double('shipping_gross_amount', 16, 2)->default(0); // shipping_charge + shipping_tax_amount
            $table->double('shipping_discount_amount', 16, 2)->default(0); 
            $table->double('shipping_sub_total', 16, 2)->default(0); // shipping_gross_amount - shipping_discount_amount
            
            $table->double('total_amount', 16, 2)->default(0); // sub_total + shipping_charge
            $table->string('attribute_set_ids', 100);
            $table->string('product_name', 255);
            $table->string('product_image', 255);
            $table->double('weight', 16, 2)->default(0);
            $table->double('wide', 16, 2)->default(0);
            $table->double('height', 16, 2)->default(0);
            $table->double('length', 16, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
