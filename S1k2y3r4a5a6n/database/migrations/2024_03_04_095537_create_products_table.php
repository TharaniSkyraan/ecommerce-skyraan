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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name',180);
            $table->string('slug',180);
            $table->text('description');
            $table->text('content');
            $table->integer('brand')->default(0);
            $table->string('category_ids',255);
            $table->integer('label_id')->default(0);
            $table->string('tax_ids',180);
            $table->string('attribute_ids',180);
            $table->text('images');
            $table->text('related_product_ids');
            $table->text('cross_selling_product_ids');
            $table->integer('rating')->default(0);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('stock_status', ['in_stock', 'out_of_stock'])->default('in_stock');
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
        Schema::dropIfExists('products');
    }
};
