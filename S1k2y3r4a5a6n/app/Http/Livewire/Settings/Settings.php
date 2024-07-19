<?php

namespace App\Http\Livewire\Settings;

use Livewire\Component;

use Livewire\WithFileUploads;
use App\Models\Setting;
use App\Models\WhyChoose;

class Settings extends Component
{
    use WithFileUploads;
    // General
    public $site_logo,$site_name,$fav_icon,$temp_site_logo,$temp_fav_icon,
           $theme_primary_color,$theme_secondary_color,$theme_tertiary_color,
           $phone,$mail_from_address,$mail_to_address,$mail_support_address,
           $mail_from_name,$mail_to_name,$mail_support_name,$address;
    
    // Notfication
    public $is_mail_enable,$is_whatsapp_enable,$is_sms_enable,$mail_driver,$mail_host,
           $mail_port,$mail_encryption,$mail_username,$mail_password,$whatsapp_token,
           $sms_gateway,$twilio_number,$twilio_auth_token,$twilio_account_sid;

    // Shipment
    public $place_order = 'common';

    public $google_map_api_key,$payment_platform,$payment_app_key,$payment_secret_key,
    $minimum_km,$cost_minimum_km,$cost_per_km,$minimum_kg,$cost_minimum_kg,$cost_per_kg,
    $is_enabled_shipping_charges;

     // why choose
     public $why_chs_title,$why_chs_desc,$why_chs_img;

    public $tab='general';

    protected $queryString = ['tab'];

    public function RemoveUploaded($param){        
        $this->reset([$param]); 
    }
    public function storegeneral()
    {

        $ipData = \Session::get('ip_config');
        $rules = [
            'site_name' => 'required|max:100|min:3',
            'theme_primary_color' => 'required|max:7|min:4', 
            'theme_secondary_color' => 'required|max:7|min:4', 
            'theme_tertiary_color' => 'required|max:7|min:4', 
            'phone' => 'required|numeric|phone:'.$ipData->code, 
            'mail_from_address' => 'required|string|max:180|email',
            'mail_to_address' => 'required|string|max:180|email',
            'mail_support_address' => 'required|string|max:180|email',
            'mail_from_name' => 'required|string|max:100',
            'mail_to_name' => 'required|string|max:100',
            'mail_support_name' => 'required|string|max:100',
            'address' => 'required|max:255', 
        ];
        if(empty($this->temp_site_logo)){
            $rules['site_logo'] = 'required|image|max:1024|mimes:svg';            
        }
        if(empty($this->temp_fav_icon)){
            $rules['fav_icon'] = 'required|file|max:1024|mimes:ico';
        }
        $validateData = $this->validate($rules);

        unset($validateData['site_logo']);
        unset($validateData['fav_icon']);
        if(!empty($this->site_logo)){
            $filename = $this->site_logo->store('setting','public');
            $validateData['site_logo'] = $filename;
        }
        if(!empty($this->fav_icon)){
            $logoname = $this->fav_icon->store('setting','public');
            $validateData['fav_icon'] = $logoname;
        }        
        Setting::updateOrCreate(
            ['id' => 1],
            $validateData
        );
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');
dd('tes');
        session()->flash('message', 'Updated Successfully.');

    }
    public function storenotification(){

        $rules = [];
        if($this->is_mail_enable){
            $rules['mail_driver'] = 'required';
            $rules['mail_host'] = 'required|max:30';
            $rules['mail_port'] = 'required|max:10';
            $rules['mail_encryption'] = 'required|max:10';
            $rules['mail_username'] = 'required|max:100';
            if($this->mail_driver=='smtp'){
                $rules['mail_password'] = 'required|max:180';
            }
        }
        if($this->is_whatsapp_enable){
            $rules['whatsapp_token'] = 'required|max:255';
        }
        if($this->is_sms_enable){
            $rules['sms_gateway'] = 'required';
            if($this->sms_gateway=='twilio'){
                $rules['twilio_number'] = 'required|max:30';
                $rules['twilio_auth_token'] = 'required|max:255';
                $rules['twilio_account_sid'] = 'required|max:255';
            }
        }
        if(count($rules)!=0){
            $validateData = $this->validate($rules);
            $validateData['is_mail_enable'] = ($this->is_mail_enable=='yes')?'yes':null;
            $validateData['is_whatsapp_enable'] = ($this->is_whatsapp_enable=='yes')?'yes':null;
            $validateData['is_sms_enable'] = ($this->is_sms_enable=='yes')?'yes':null;
            Setting::updateOrCreate(
                ['id' => 1],
                $validateData
            );
        }
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');

        session()->flash('message', 'Updated Successfully.');
        
    }
    public function storeshipment(){
        
        $rules = [
            'place_order' => 'required',
            'google_map_api_key' => 'required|max:100',
            'payment_platform' => 'required',
            'payment_app_key' => 'required|max:100',
            'payment_secret_key' => 'required|max:100',
        ];
        
        if($this->is_enabled_shipping_charges){
            $rules['minimum_km'] = 'required|numeric';        
            $rules['cost_minimum_km'] ='required|numeric';
            $rules['cost_per_km'] = 'required|numeric';
            $rules['minimum_kg'] = 'required|numeric';     
            $rules['cost_minimum_kg'] = 'required|numeric';
            $rules['cost_per_kg'] = 'required|numeric';
        }
        $validateData = $this->validate($rules);
        $validateData['is_enabled_shipping_charges'] = ($this->is_enabled_shipping_charges=='yes')?'yes':null;
        Setting::updateOrCreate(
            ['id' => 1],
            $validateData
        );
        
        \Artisan::call('config:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('config:cache');

        session()->flash('message', 'Updated Successfully.');
    }

    public function storewhychoose(){
        $rules = [
            'why_chs_title' => 'required|max:100|min:3',
            'why_chs_desc' => 'required|max:255|min:10', 
        ];
        if(empty($this->why_chs_img)){
            $rules['why_chs_img'] = 'required|image|max:1024|mimes:svg';            
        }
        $validateData = $this->validate($rules);
        unset($validateData['why_chs_img']);
        if(!empty($this->why_chs_img)){
            $filename = $this->why_chs_img->store('setting','public');
            $validateData['why_chs_img'] = $filename;
        }
        WhyChoose::updateOrCreate(
            ['id' => 0],
            $validateData
        );

        session()->flash('message', 'Updated Successfully.');
    }
    public function mount(){
        
        $setting = Setting::first();

        if(isset($setting)){
    
            $this->site_name = $setting->site_name;
            $this->temp_site_logo = $setting->site_logo;
            $this->temp_fav_icon = $setting->fav_icon;
    
            $this->theme_primary_color  = $setting->theme_primary_color;
            $this->theme_secondary_color = $setting->theme_secondary_color;
            $this->theme_tertiary_color = $setting->theme_tertiary_color;
            $this->phone = $setting->phone;
    
            $this->mail_from_address = $setting->mail_from_address;
            $this->mail_to_address = $setting->mail_to_address;
            $this->mail_support_address = $setting->mail_support_address;
            $this->mail_from_name = $setting->mail_from_name;
            $this->mail_to_name = $setting->mail_to_name;
            $this->mail_support_name = $setting->mail_support_name;
            $this->address = $setting->address;
    
            $this->is_mail_enable = $setting->is_mail_enable;
            $this->is_whatsapp_enable = $setting->is_whatsapp_enable;
            $this->is_sms_enable = $setting->is_sms_enable;
    
            if($this->is_mail_enable){
                $this->mail_driver = $setting->mail_driver;        
                $this->mail_host = $setting->mail_host;
                $this->mail_port = $setting->mail_port;
                $this->mail_encryption = $setting->mail_encryption;
                $this->mail_username = $setting->mail_username;
                $this->mail_password = $setting->mail_password;
            }
            if($this->is_whatsapp_enable){
                $this->whatsapp_token = $setting->whatsapp_token;
            }
            if($this->is_sms_enable){
                $this->sms_gateway = $setting->sms_gateway;
                $this->twilio_number = $setting->twilio_number;
                $this->twilio_auth_token = $setting->twilio_auth_token;
                $this->twilio_account_sid = $setting->twilio_auth_token;
            }
    
            // Shipment
            $this->is_enabled_shipping_charges = $setting->is_enabled_shipping_charges;

            if($this->is_enabled_shipping_charges){
                $this->minimum_km = $setting->minimum_km;        
                $this->cost_minimum_km = $setting->cost_minimum_km;
                $this->cost_per_km = $setting->cost_per_km;
                $this->minimum_kg = $setting->minimum_kg;        
                $this->cost_minimum_kg = $setting->cost_minimum_kg;
                $this->cost_per_kg = $setting->cost_per_kg;
            }
            
            $this->place_order = $setting->place_order??'common';
            $this->google_map_api_key = $setting->google_map_api_key;
            $this->payment_platform = $setting->payment_platform;
            $this->payment_app_key = $setting->payment_app_key;
            $this->payment_secret_key = $setting->payment_secret_key;
         
            // why choose
            $this->why_chs_title = $setting->why_chs_title;
            $this->why_chs_img = $setting->why_chs_img;
            $this->why_chs_desc = $setting->why_chs_desc;
        }
    }
    public function render()
    {
        return view('livewire.settings.settings');
    }
}
