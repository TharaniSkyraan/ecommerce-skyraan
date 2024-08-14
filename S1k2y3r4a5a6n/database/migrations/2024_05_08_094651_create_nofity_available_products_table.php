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
        Schema::create('nofity_available_products', function (Blueprint $table) {
            $table->id();
            $table->string('email',180);
            $table->integer('product_id')->default(0);
            $table->integer('product_variant_id')->default(0);
            $table->integer('attempts')->default(0);
            $table->enum('status', ['0', '1'])->default('0');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nofity_available_products');
    }
};
