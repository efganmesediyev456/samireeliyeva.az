<?php

namespace App\Http\Middleware;

use App\Models\Subcategory;
use Closure;
use Illuminate\Http\Request;

class SubscribedMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated
        if (!auth('api')->check()) {
            return redirect()->route('login');
        }
        $subcategory = Subcategory::get()->filter(function ($q) use ($request) {
                return $q->slug == $request->route('slug');
            })->first();

        if (!auth('api')->user()->hasActiveSubscription($subcategory)) {
            return response()->json([
                "status"=>"error",
                "message" => 'You need an active subscription to access this content.',
                "data" => [],
                "route"=>null
            ], 403);
        }
        
        return $next($request);
    }
}