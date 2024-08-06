<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable =['site_logo','site_name','fav_icon','temp_site_logo','temp_fav_icon',
            'theme_primary_color','theme_secondary_color','theme_tertiary_color','phone',
            'mail_from_address','mail_to_address','mail_support_address','mail_from_name',
            'mail_to_name','mail_support_name','address','footer_content','is_mail_enable',
            'is_whatsapp_enable','is_sms_enable','mail_driver','mail_host',
            'mail_port','mail_encryption','mail_username','mail_password','whatsapp_token',
            'sms_gateway','twilio_number','twilio_auth_token','twilio_account_sid','google_map_api_key',
            'payment_platform','payment_app_key','payment_secret_key','place_order','is_enabled_default_tax','default_tax',
            'is_enabled_shipping_tax','shipping_tax','is_enabled_shipping_charges','minimum_km','cost_minimum_km','cost_per_km',
            'minimum_kg','cost_minimum_kg','cost_per_kg'];

}
