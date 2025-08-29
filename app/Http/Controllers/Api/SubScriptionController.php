<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OurOnMapResource;
use App\Http\Resources\VacancyCollection;
use App\Models\OurOnMap;
use App\Models\Property;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Resources\PropertyResource;
use App\Http\Resources\SubScriptionResource;
use Auth;
class SubScriptionController extends Controller
{
    public function index(Request $request)
    {
       $user =  Auth::guard("api")->user();
       $subscriptions = $user->orders()->where('status','completed')->orderBy("id","desc")->get();
       return SubScriptionResource::collection($subscriptions);
    }
}