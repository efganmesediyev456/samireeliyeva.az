<?php

namespace App\Http\Controllers\Api;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogCollection;
use App\Http\Resources\CatalogResource;
use App\Http\Resources\GalleryResource;
use App\Models\Catalog;
use App\Models\GalleryPhoto;
use App\Models\GalleryVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllPhotos(Request $request)
    {
        $galleries = GalleryPhoto::status()->order()->paginate(12);

        return GalleryResource::collection($galleries);
    }


    public function singlePhoto(Request $request, $slug){
        $galleryPhoto = GalleryPhoto::status()->whereHas('translations', function($query)use($slug){
            return $query->where('slug', $slug)->where('locale',app()->getLocale());
        })->first();
        if(is_null($galleryPhoto)){
            return $this->responseMessage("error",'No found blog',null, 400);
        }
        return new GalleryResource($galleryPhoto);
    }

    public function getAllVideos(Request $request)
    {
        $galleries = GalleryVideo::status()->order()->paginate(12);
        return GalleryResource::collection($galleries);
    }


    public function singleVideo(Request $request, $slug){
        $galleryPhoto = GalleryVideo::status()->whereHas('translations', function($query)use($slug){
            return $query->where('slug', $slug)->where('locale',app()->getLocale());
        })->first();
        if(is_null($galleryPhoto)){
            return $this->responseMessage("error",'No found gallery media',null, 400);
        }
        return new GalleryResource($galleryPhoto);
    }
}
