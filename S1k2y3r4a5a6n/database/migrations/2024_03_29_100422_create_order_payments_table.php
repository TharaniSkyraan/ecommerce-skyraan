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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->default(0);
            $table->string('charge_id',180);
            $table->integer('user_id')->default(0);
            $table->string('currency',20);
            $table->string('payment_chennal',30);
            $table->string('description',30)->nullable();            
            $table->double('amount', 16, 2)->default(0);
            $table->enum('status', ['pending', 'refund', 'refund_request', 'completed'])->default('pending');
            $table->enum('payment_type', ['pending', 'confirm'])->default('pending');    
            $table->double('refunded_amount', 16, 2)->default(0);
            $table->string('refund_note',180)->nullable();
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
        Schema::dropIfExists('order_payments');
    }
};
