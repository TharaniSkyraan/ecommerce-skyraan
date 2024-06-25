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
        Schema::create('stock_histories', function (Blueprint $table) {
            $table->id();  
            $table->bigInteger('reference_number')->default(0);
            $table->enum('stock_type', ['upload', 'transfer'])->default('upload');
            $table->unsignedBigInteger('warehouse_from_id'); 
            $table->unsignedBigInteger('warehouse_to_id'); 
            $table->dateTime('sent_date')->nullable();
            $table->dateTime('received_date')->nullable();
            $table->enum('status', ['sent', 'received'])->default('received');           
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_histories');
    }
};
