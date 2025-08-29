<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\TextbookBannerResource;
use App\Models\TextbookBanner;

class TextBookBannerController extends Controller
{
    public function index(){
        return new TextbookBannerResource(TextbookBanner::first());
    }
}
