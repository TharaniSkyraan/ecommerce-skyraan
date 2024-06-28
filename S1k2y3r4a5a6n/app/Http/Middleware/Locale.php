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

        $ip = $request->ip();   
        $ipData = $this->getCity($ip??'183.82.250.192');   
                dd($ipData); 
        $country = \App\Models\Country::where('code',$ipData??'IN')->first();
        session(['ip_config' => $country]);
        view()->share('ip_data',Session::get('ip_config'));

        if(Session::has('ip_config')==false)
        {

        }else{
            view()->share('ip_data',Session::get('ip_config'));
        }
        return $next($request);
    }

}
