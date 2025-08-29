<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('Accept-Language')) {
            $acceptLang = $request->header('Accept-Language');

            if(strlen($acceptLang)>2){
                $locale = 'az';
            }else{
                            $locale = substr($acceptLang, 0, 2); 

            }


            if (in_array($locale, ['en', 'az', 'ru'])) {
                App::setLocale($locale);
            }else{
                App::setLocale('az');
            }
            

        }
        

        return $next($request);
    }
}
