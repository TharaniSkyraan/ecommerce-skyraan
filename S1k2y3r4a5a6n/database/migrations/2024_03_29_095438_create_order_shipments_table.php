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
        Schema::create('order_shipments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('order_id')->default(0);
            $table->integer('user_id')->default(0);
            $table->double('weight', 16, 2)->default(0);
            $table->string('status', 30)->default('pending');
            $table->double('cod_amount', 16, 2)->default(0);
            $table->enum('cod_status', ['pending', 'completed'])->nullable();
            $table->enum('cross_checking_status', ['pending', 'completed'])->default('pending');
            $table->string('tracking_id', 255);
            $table->string('shipping_company_name', 180)->nullable();
            $table->string('tracking_link', 255)->nullable();
            $table->timestamp('estimate_date_shipped')->nullable();
            $table->timestamp('date_shipped')->nullable();
            $table->string('note', 255)->nullable();
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
        Schema::dropIfExists('order_shipments');
    }
};
