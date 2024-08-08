<?php

namespace App\Http\Middleware;

use App\Traits\GeoIPService;
use App\Traits\ZoneConfig;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use App\Models\Zone;
use Illuminate\Support\Facades\Auth;

class Locale
{
    use GeoIPService, ZoneConfig;

    public function handle($request, Closure $next)
    {
        $this->handleZoneConfig($request);
        $this->handleIpConfig($request);

        return $next($request);
    }

    protected function handleZoneConfig($request)
    {
        $zone_config = Session::has('zone_config');
        $zone_data = Session::get('zone_config');
        if ($zone_config) {
            if (!empty($zone_data['zone_id'])) {
                $zones = Zone::where('id',$zone_data['zone_id'])->whereStatus('active')->first();
                if (isset($zone) && ($zone_data['warehouse_ids'] == $zones->warehouse_ids)) {
                    View::share('zone_data', $zone_data);
                }
                else{$zone_config = false;}
            }
            else{$zone_config = false;}
        }

        if (!$zone_config || empty($zone_data['postal_code'])) {
            $address = $this->getUserAddress();
            if ($address) {
                $ipLocationData = $this->getIpLocationDataFromAddress($address);
                $this->setZoneConfig($ipLocationData);
            } else {
                $ip ='183.82.250.192';   
                // $ip = $request->ip();  
                $ipLocationData = $this->getIpLocationDataFromIp($ip);
                $this->setZoneConfig($ipLocationData);
            }
        }
    }

    protected function handleIpConfig($request)
    {
        if (!Session::has('ip_config')) {
            $ip ='183.82.250.192';   
            // $ip = $request->ip();   
            $ipData = $this->getIpData($ip);
            if ($ipData) {
                $country = \App\Models\Country::where('code', $ipData['country_code'] ?? 'IN')->first();
                Session::put('ip_config', $country);
                View::share('ip_data', $country);
            }
        } else {
            View::share('ip_data', Session::get('ip_config'));
        }
    }

    protected function getUserAddress()
    {
        if (Auth::check()) {
            return Auth::user()->usercart->address ?? Auth::user()->address;
        }
        return null;
    }

    protected function getIpLocationDataFromAddress($address)
    {
        return [
            'address_id' => $address->id ?? '',
            'city' => $address->city ?? '',
            'latitude' => '',
            'longitude' => '',
            'postal_code' => $address->postal_code ?? ''
        ];
    }

    protected function getIpLocationDataFromIp($ip)
    {
        $ipLocationData = $this->getCity($ip);
        if (!$ipLocationData || empty($ipLocationData['postal_code'])) {
            $ipLocationData = @json_decode(file_get_contents("https://ipinfo.io/{$ip}"));
            if ($ipLocationData) {
                $ipLocationData = [
                    'address_id' => '',
                    'city' => $ipLocationData->city ?? '',
                    'latitude' => explode(',', $ipLocationData->loc)[0] ?? '',
                    'longitude' => explode(',', $ipLocationData->loc)[1] ?? '',
                    'postal_code' => $ipLocationData->postal ?? ''
                ];
            }
        }
        return $ipLocationData;
    }

    protected function setZoneConfig($ipLocationData)
    {
        if ($ipLocationData) {
            $result = $this->configzone($ipLocationData);
            Session::put('zone_config', $result);
            View::share('zone_data', $result);
        }
    }

    protected function getIpData($ip)
    {
        $ipData = $this->getCity($ip);
        if (!$ipData || empty($ipData['country_code'])) {
            $ipData = @json_decode(file_get_contents("https://ipinfo.io/{$ip}"));
            if ($ipData) {
                $ipData = ['country_code' => $ipData->country ?? ''];
            }
        }
        return $ipData;
    }
}
