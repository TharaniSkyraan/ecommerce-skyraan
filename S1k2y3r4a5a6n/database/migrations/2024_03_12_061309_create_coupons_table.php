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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('coupon_code',30);
            $table->text('terms_and_condition');
            $table->bigInteger('count')->default(0);
            $table->enum('unlimited_coupon', ['yes', 'no'])->default('no');
            $table->enum('display_at_checkout', ['yes', 'no'])->default('no');
            $table->double('discount', 16, 2)->notNullable();
            $table->double('minimum_order', 16, 2)->notNullable();
            $table->enum('discount_type', ['flat', 'percentage', 'free_shipping'])->default('flat');
            $table->enum('apply_for', ['all-orders', 'minimum-order', 'category', 'collection', 'customer', 'product', 'once-per-customer'])->default('all-orders');
            $table->string('apply_for_ids')->default('0');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->enum('never_expired', ['yes', 'no'])->default('no');
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
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
        Schema::dropIfExists('coupons');
    }
};
