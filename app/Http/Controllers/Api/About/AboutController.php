<?php

namespace App\Http\Controllers\Api\About;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\ExamBanner;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\ExamBannerResource;

class AboutController extends Controller
{
    public function index(){
        return new AboutResource(About::first());
    }


    public function exam(){
                return new ExamBannerResource(ExamBanner::first());
    }
}
