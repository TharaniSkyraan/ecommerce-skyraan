<?php

namespace App\Http\Middleware;

use App\Traits\GeoIPService;
use App\Traits\ZoneConfig;
use Closure, Session, View, App\Models\SavedAddress;
use Auth;
use Cookie;

class Locale
{

    use GeoIPService, ZoneConfig;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {

        if(Session::has('zone_config')==false || empty(Session::get('zone_config')['postal_code']))
        {  

            if(Auth::check() && (isset(Auth::user()->address) || isset(Auth::user()->usercart->address)))
            {
                $address = Auth::user()->usercart->address??Auth::user()->address;

                $ipLocationData = array(
                    'address_id' => $address->id??'',
                    'city' => $address->city??'',
                    'latitude' => '',
                    'longitude' => '',
                    'postal_code' => $address->postal_code??''
                );  
                if($ipLocationData && $ipLocationData!=null)
                {
                    $result = $this->configzone($ipLocationData); 
                    session(['zone_config' => $result]);
                    view()->share('zone_data',\Session::get('zone_config'));
                }
            }else{
                
                $ip = $request->ip();    

                $ipLocationData = $this->getCity('183.82.250.192');    
                if($ipLocationData == null || empty($ipLocationData['postal_code'])){
                    $ipLocationData = @json_decode(file_get_contents("https://ipinfo.io/".'183.82.250.192'));  
                    if($ipLocationData && $ipLocationData!=null)
                    {
                        $ipLocationData = array(
                            'address_id' => '',
                            'city' => $ipLocationData->city??'',
                            'latitude' => (isset($ipLocationData->loc))?explode(',',$ipLocationData->loc)[0]:'',
                            'longitude' => (isset($ipLocationData->loc))?explode(',',$ipLocationData->loc)[1]:'',
                            'postal_code' => $ipLocationData->postal??''
                        );  
                    }   
                }
                
                if($ipLocationData && $ipLocationData!=null)
                {
                    $result = $this->configzone($ipLocationData); 
                    session(['zone_config' => $result]);
                    view()->share('zone_data',\Session::get('zone_config'));
                }
            }

        }else{
            view()->share('zone_data',Session::get('zone_config'));
        }
        
        if(Session::has('ip_config')==false)
        {
            $ip = $request->ip();   
       
            $ipData = $this->getCity('183.82.250.192');   
            if($ipData == null || empty($ipData['country_code'])){  
                $ipData = @json_decode(file_get_contents("https://ipinfo.io/".'183.82.250.192'));    
                if($ipData && $ipData!=null)
                {
                    $ipData = array('country_code' => $ipData->geoplugin_countryCode??''); 
                }
            }
            
            if($ipData && $ipData!=null)
            {
                $country = \App\Models\Country::where('code',$ipData['country_code']??'IN')->first();
                session(['ip_config' => $country]);
                view()->share('ip_data',Session::get('ip_config'));
            }
        }else{
            view()->share('ip_data',Session::get('ip_config'));
        }   

        return $next($request);
    }

}
