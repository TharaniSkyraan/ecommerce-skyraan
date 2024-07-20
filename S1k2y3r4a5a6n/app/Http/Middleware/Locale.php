<?php

namespace App\Http\Middleware;

use App\Traits\GeoIPService;
use Closure, Session, View, App\Models\Zone;
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
               
                $zone_id = null;
                $warehouse_ids = '';

                $x = $ipLocationData['latitude'];
                $y = $ipLocationData['longitude'];
                $inside = false;

                $zones = Zone::whereStatus('active')->get();

                foreach($zones as $zone){

                    $polygon = json_decode($zone->zone_coordinates);
                    $vertices = count($polygon);
                    if(!$inside){
                        for ($i = 0, $j = $vertices - 1; $i < $vertices; $j = $i++) {
                            
                            $xi = $polygon[$i]->lat;
                            $yi = $polygon[$i]->lng;
                            $xj = $polygon[$j]->lat;
                            $yj = $polygon[$j]->lng;
                    
                            $intersect = (($yi > $y) != ($yj > $y)) && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi) + $xi);
                        
                            if ($intersect) $inside = !$inside;
                        }
                        if ($inside) {
                            $zone_id = $zone->id;
                            $warehouse_ids = $zone->warehouse_ids;
                            break; // Stop looping once inside is true
                        }
                    }
                    
                }

                $country->zone_id = $zone_id;
                $country->warehouse_ids = $warehouse_ids;

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
