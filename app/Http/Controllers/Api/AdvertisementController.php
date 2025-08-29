<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\BlogAndNewsResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\Advertisement;
use App\Models\BlogNew;
use App\Models\Language;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    public function index(){
        return AdvertisementResource::collection(Advertisement::status()->order()->paginate(12));
    }


    public function single(Request $request, $slug){
        $galleryPhoto = Advertisement::status()->whereHas('translations', function($query)use($slug){
            return $query->where('slug', $slug)->where('locale',app()->getLocale());
        })->first();
        $galleryPhoto->increment("view");
        if(is_null($galleryPhoto)){
            return $this->responseMessage("error",'No found item',null, 400);
        }
        return new AdvertisementResource($galleryPhoto);
    }



    public function others(Request $request){
        $this->validate($request, [
            'item_id'=>"required|exists:advertisements,id"
        ]);
        $blogs=Advertisement::status()->whereNot("id", $request->item_id)->orderBy('id','desc')->limit(12)->get();
        return AdvertisementResource::collection($blogs);
    }
}
