<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TextBookResource;
use App\Http\Resources\VacancyBannerResource;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyReceipentResource;
use App\Http\Resources\VacancyResource;
use App\Http\Resources\VacancyShareLinkResource;
use App\Models\Textbook;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use App\Models\VacancyBanner;
use App\Models\VacancyReceipent;
use App\Models\VacancyShareSocial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TextBookController extends Controller
{
    public function index(Request $request)
    {
        $textbooks = Textbook::status()->orderBy('id','desc')->paginate(12);
       

        return TextBookResource::collection($textbooks);
    }

    public function single(Request $request, $item){
        $item = Textbook::status()->whereHas('translations', function($query)use($item){
            return $query->where('slug', $item)->where('locale',app()->getLocale());
        })->first();
        if(is_null($item)){
            return $this->responseMessage("error",'No found blog',null, 400);
        }
        return new TextBookResource($item);
    }

}
