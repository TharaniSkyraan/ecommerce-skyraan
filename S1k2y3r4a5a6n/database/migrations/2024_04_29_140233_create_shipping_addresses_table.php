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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Reference column should be unsigned
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('order_id'); // Reference column should be unsigned
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('name',30);
            $table->string('phone',20); 
            $table->string('alternative_phone',20)->nullable(); 
            $table->string('country',5);
            $table->string('city',100);
            $table->string('state',100);
            $table->string('address',255);
            $table->string('landmark',255)->nullable(); 
            $table->string('postal_code',20);
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
        Schema::dropIfExists('shipping_addresses');
    }
};
