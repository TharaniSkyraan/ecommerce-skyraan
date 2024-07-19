<?php

namespace App\Http\Middleware;

use App\Traits\GeoIPService;
use Closure, Session, View;
use Auth;
use Cookie;

class Locale
{

    use GeoIPService;
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

        if(Session::has('zone_config')==false)
        {  
            $ip = $request->ip();   

            $ipLocationData = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip??'183.82.250.192'));    
         
            if($ipLocationData == null){
                $ipLocationData = $this->getCity($ip??'183.82.250.192');   
            }else{            
                $ipLocationData = array(
                    'country_code' => $ipLocationData->geoplugin_countryCode??'',
                    'latitude' => $ipLocationData->geoplugin_latitude??'',
                    'longitude' => $ipLocationData->geoplugin_longitude??''
                );        
            }
            
            if($ipLocationData && $ipLocationData!=null)
            {
                $country = \App\Models\Country::where('code',$ipLocationData['country_code']??'IN')->first();
                session(['zone_config' => $country]);
                view()->share('zone_data',Session::get('zone_config'));
            }

        }else{
            view()->share('zone_data',Session::get('zone_config'));
        }
        

        if(Session::has('ip_config')==false)
        {
            $ip = $request->ip();   
       
            $ipData = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip??'183.82.250.192'));    
            if($ipData == null){
                $ipData = $this->getCity($ip??'183.82.250.192');   
            }else{            
                $ipData = array(
                    'country_code' => $ipData->geoplugin_countryCode??'',
                    'latitude' => $ipData->geoplugin_latitude??'',
                    'longitude' => $ipData->geoplugin_longitude??''
                );        
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
