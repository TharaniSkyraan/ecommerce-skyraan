<?php

namespace App\Traits;
use App\Models\Zone;
use Auth;

trait ZoneConfig
{

    public function ipzone()
    {
        if((isset(Auth::user()->address) || isset(Auth::user()->usercart->address)))
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
            view()->share('zone_data',\Session::get('zone_config')); 
        }
    
    }

    public function configzone($data)
    {
        if((empty($data['latitude']) || empty($data['longitude'])) && !empty($data['postal_code']))
        {
            
            $result = @json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$data['postal_code']."&key=".config('shipping.google_map_api_key')));
            
            if(empty($result)){
                $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$data['postal_code']."&key=".config('shipping.google_map_api_key');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $response = curl_exec($ch);
                if (curl_errno($ch)) {
                    // Handle curl error
                    echo 'Curl error: ' . curl_error($ch);
                } else {
                    // Process the response
                    $result = json_decode($response, true);
                    // Further processing
                }
                curl_close($ch);
                $data['latitude'] = $result['results'][0]['geometry']['location']['lat']??0;
                $data['longitude'] = $result['results'][0]['geometry']['location']['lng']??0;
            }else{
                $data['latitude'] = $result->results[0]->geometry->location->lat??0;
                $data['longitude'] = $result->results[0]->geometry->location->lng??0;
            }
           
        }else

        if(empty($data['postal_code']) && (!empty($data['latitude']) && !empty($data['longitude'])))
        {
            
            $decodedResponse = @json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?latlng=".$data['latitude'].",".$data['longitude']."&sensor=false&key=".config('shipping.google_map_api_key')));

            $addressComponents = $decodedResponsel->results[0]->address_components;
            foreach ($addressComponents as $component) {
                $types = $component->types;
                $longName = $component->long_name;
                if (in_array('postal_code', $types)) {
                    $data['postal_code'] = $longName;
                }
                if(empty($data['city']))
                { 
                    if(in_array('locality', $types)){
                        $locality = $longName;
                    } elseif (in_array('administrative_area_level_1', $types)){
                        $region1 = $longName;
                    }elseif (in_array('administrative_area_level_2', $types)){
                        $region2 = $longName;
                    }elseif (in_array('administrative_area_level_3', $types)){
                        $region3 = $longName;
                    }
                    $data['city'] = isset($region3) ? trim($region3) : (isset($locality) ? $locality : (isset($region2) ? $region2 : null));
                }
            }
        }
        
        $zone_id = null;
        $warehouse_ids = '';

        $x = $data['latitude'];
        $y = $data['longitude'];
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

        $data['zone_id'] = $zone_id;
        $data['warehouse_ids'] = $warehouse_ids;

        return $data;
    }

}