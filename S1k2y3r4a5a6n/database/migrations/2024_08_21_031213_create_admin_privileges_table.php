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
        Schema::create('admin_privileges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id'); // Reference column should be unsigned
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->integer('module_id');
            $table->text('privileges')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_privileges');
    }
};
