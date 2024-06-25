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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code',100)->nullable();
            $table->string('invoice_number',100)->nullable();
            $table->integer('user_id')->default(0);
            $table->string('coupon_code', 30)->nullable();
            $table->double('sub_total', 16, 2)->default(0);
            $table->double('total_amount', 16, 2)->default(0);
            $table->double('tax_amount', 16, 2)->default(0);
            $table->double('shipping_amount', 16, 2)->default(0);
            $table->double('discount_amount', 16, 2)->default(0);
            $table->string('description', 255)->nullable();
            $table->string('status', 30)->nullable();
            $table->enum('is_confirmed', ['1', '0'])->default('0');
            $table->enum('is_finished', ['1', '0'])->default('0');
            $table->integer('payment_id')->default(0);
            $table->softDeletes();
            $table->timestamp('invoice_date')->nullable(); 
            $table->timestamp('completed_at')->nullable(); 
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
       
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
