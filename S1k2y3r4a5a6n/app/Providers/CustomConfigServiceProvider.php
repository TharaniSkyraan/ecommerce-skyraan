<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Models\Setting;
use View;

class CustomConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        
        if ($setting = Setting::first()) {

            $this->app['config']['siteSetting'] = [
                
                'site_name' => $setting->site_name,

                'site_logo' => asset('storage').'/'.$setting->site_logo,
            ];
            
            $this->app['config']['mail'] = [

                'driver' => $setting->mail_driver,

                'host' => $setting->mail_host,

                'port' => $setting->mail_port,

                'from' => [

                    'address' => $setting->mail_from_address,

                    'name' => $setting->mail_from_name

                ], 

                'receive_to' => [

                    'address' => $setting->mail_to_address,

                    'name' => $setting->mail_to_name
                ],
                'support_receive_to' => [

                    'address' => $setting->mail_support_address,

                    'name' => $setting->mail_support_name
                ],

                'encryption' => $setting->mail_encryption,

                'username' => $setting->mail_username,

                'password' => $setting->mail_password,

            ];
            
            $this->app['config']['services'] = [

                'is_mail_enable' => $setting->is_mail_enable,
                
                'is_whatsapp_enable' => $setting->is_whatsapp_enable,

                'is_sms_enable' => $setting->is_sms_enable,

                'whatsapp' => [
                    
                    'access_token' => $setting->whatsapp_token,

                ],

                'sms_gateway' => $setting->sms_gateway,

                'twilio' => [

                    'twilio_number' => $setting->twilio_number,
                    
                    'twilio_auth_token' => $setting->twilio_auth_token,
                    
                    'twilio_account_sid' => $setting->twilio_account_sid,

                ],

            ];
            
            $this->app['config']['shipping'] = [

                'payment_platform' => $setting->payment_platform,

                'razorpay' => [

                    'razorpay_key' => $setting->payment_app_key,

                    'razorpay_secret' => $setting->payment_secret_key,

                ],
                'google_map_api_key' => $setting->google_map_api_key,
            ];
            View::share(['siteSetting' => $setting]);
        }
    }
}
