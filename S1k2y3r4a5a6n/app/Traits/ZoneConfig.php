<?php

namespace App\Traits;
use App\Models\Zone;
use Auth;

trait ZoneConfig
{

    public function ipzone()
    {
        if((isset(Auth::user()->address) || isset(Auth::user()->usercart)))
        {
            $address = Auth::user()->usercart??Auth::user()->address;

            $ipLocationData = array(
                'city' => $address->city??'',
                'latitude' => '',
                'longitude' => '',
                'postal_code' => $address->postal_code??($address->postal_code??'')
            );  
            if($ipLocationData && $ipLocationData!=null)
            {
                $this->configzone($ipLocationData); 
            }
        }else{
            $ip = $request->ip();    

            $ipLocationData = $this->getCity($ip??'183.82.250.192');    
            if($ipLocationData == null || empty($ipLocationData['postal_code'])){
                $ipLocationData = @json_decode(file_get_contents("https://ipinfo.io/".$ip??'183.82.250.192'));  
                if($ipLocationData && $ipLocationData!=null)
                {
                    $ipLocationData = array(
                        'city' => $ipLocationData->city??'',
                        'latitude' => (isset($ipLocationData->loc))?explode(',',$ipLocationData->loc)[0]:'',
                        'longitude' => (isset($ipLocationData->loc))?explode(',',$ipLocationData->loc)[1]:'',
                        'postal_code' => $ipLocationData->postal??''
                    );  
                }   
            }
            
            if($ipLocationData && $ipLocationData!=null)
            {
                $this->configzone($ipLocationData); 
            }

        }
    
    }

    public function configzone($data)
    {
        if((empty($data['latitude']) || empty($data['longitude'])) && !empty($data['postal_code']))
        {
            $result = @json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=".$data['postal_code']."&sensor=false&key=".config('shipping.google_map_api_key')));

            $data['latitude'] = $result->results[0]->geometry->location->lat??0;
            $data['longitude'] = $result->results[0]->geometry->location->lng??0;
        }

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
                    if (in_array('locality', $types)) {
                        $locality = $longName;
                    } elseif (in_array('administrative_area_level_1', $types)) {
                        $region1 = $longName;
                    } elseif (in_array('administrative_area_level_2', $types)) {
                        $region2 = $longName;
                    }elseif (in_array('administrative_area_level_3', $types)) {
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
        session(['zone_config' => $data]);
        view()->share('zone_data',\Session::get('zone_config'));
    
    }

}