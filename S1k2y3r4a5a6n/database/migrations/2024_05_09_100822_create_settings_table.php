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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('site_name',180)->nullable();
            $table->string('site_logo',255)->nullable();
            $table->string('gst_number',30)->nullable();
            $table->string('footer_content',255)->nullable();
            $table->string('fav_icon',255)->nullable();
            $table->string('theme_primary_color',30)->nullable();
            $table->string('theme_secondary_color',30)->nullable();
            $table->string('theme_tertiary_color',30)->nullable();
            $table->string('phone',20)->nullable(); 
            $table->string('mail_from_address',180)->nullable(); 
            $table->string('mail_from_name',100)->nullable(); 
            $table->string('mail_to_address',180)->nullable(); 
            $table->string('mail_to_name',100)->nullable(); 
            $table->string('mail_support_address',180)->nullable(); 
            $table->string('mail_support_name',100)->nullable(); 
            $table->string('address',255)->nullable(); 
            $table->enum('is_mail_enable', ['yes'])->nullable();
            $table->string('mail_driver',30)->nullable();
            $table->string('mail_host',30)->nullable();
            $table->string('mail_port',10)->nullable();
            $table->string('mail_encryption',10)->nullable();
            $table->string('mail_username',100)->nullable();
            $table->string('mail_password',180)->nullable();
            $table->enum('is_whatsapp_enable', ['yes'])->nullable();
            $table->string('whatsapp_token',255)->nullable();
            $table->enum('is_sms_enable', ['yes'])->nullable();
            $table->string('sms_gateway',30)->nullable();
            $table->string('twilio_number',30)->nullable();
            $table->string('twilio_auth_token',180)->nullable();
            $table->string('twilio_account_sid',180)->nullable();
            $table->enum('place_order', ['separate','common'])->default('common');
            $table->string('google_map_api_key',100)->nullable();
            $table->string('payment_platform',30)->nullable();
            $table->string('payment_app_key',100)->nullable();
            $table->string('payment_secret_key',100)->nullable();
            $table->enum('is_enabled_default_tax', ['yes'])->nullable();
            $table->enum('is_enabled_shipping_tax', ['yes'])->nullable();
            $table->integer('default_tax')->nullable();   
            $table->integer('shipping_tax')->nullable();   
            $table->double('minimum_km', 16, 2)->default(0);   
            $table->double('cost_minimum_km', 16, 2)->default(0);   
            $table->double('cost_per_km', 16, 2)->default(0);   
            $table->double('minimum_kg', 16, 2)->default(0);   
            $table->double('cost_minimum_kg', 16, 2)->default(0);   
            $table->double('cost_per_kg', 16, 2)->default(0);   
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
