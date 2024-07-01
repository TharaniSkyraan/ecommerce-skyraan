<?php

namespace App\Traits;

use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;

trait GeoIPService
{
    protected $reader;

    public function __construct()
    {
        $this->reader = new Reader(storage_path('app/GeoLite2-City.mmdb'));
    }

    public function getCity($ip)
    {
        try {
            $record = $this->reader->city($ip);
            
            return [
                'latitude' => $record->location->latitude??'',
                'longitude' => $record->location->longitude??'',
                'country_code' => $record->country->isoCode??'IN'
            ];

        } catch (AddressNotFoundException $e) {
            return null;
        }
    }
}
