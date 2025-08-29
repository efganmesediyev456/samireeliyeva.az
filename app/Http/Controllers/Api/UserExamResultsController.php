<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OurOnMapResource;
use App\Http\Resources\VacancyCollection;
use App\Models\OurOnMap;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Resources\UserExamStartResource;

class UserExamResultsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth("api")->user();
        $datas = $user->userExamStarts()
            ->get()
            ->filter(function ($item) {
                return $item->userExams->isNotEmpty(); 
            });
        return UserExamStartResource::collection($datas);
    }
}