<?php

namespace App\Http\Controllers\Api;

// Gerekli kullanımlar

use App\Http\Controllers\Controller;
use App\Http\Resources\AdvertisementResource;
use App\Http\Resources\BannerDetailResource;
use App\Http\Resources\BannerResource;
use App\Http\Resources\BlogAndNewsResource;
use App\Http\Resources\BrendResource;
use App\Http\Resources\ImportantLinkResource;
use App\Http\Resources\Products\CategoryResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\SocialLinkResource;
use App\Http\Resources\TextBookResource;
use App\Models\Advertisement;
use App\Models\Banner;
use App\Models\BlogNew;
use App\Models\Brend;
use App\Models\Category;
use App\Models\EducationalRegion;
use App\Models\ImportantLink;
use App\Models\Language;
use App\Models\Partner;
use App\Models\Product;
use App\Models\BannerDetail;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Models\Textbook;
use Illuminate\Http\Request;

class HomeController extends Controller
{


     private function getTranslatedSlugs($item): array
    {
        $languages = Language::pluck('code')->toArray();

        $slugs = [];
        foreach ($languages as $lang) {
            $slugs[$lang] = $item->translate($lang)->slug ?? null;
        }

        return $slugs;
    }


    public function getSocialLinks()
    {
        $items = SocialLink::latest()->get();
        return SocialLinkResource::collection($items);
    }

    public function categories()
    {
        $categories = Category::status()->get();
        return $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $this->getTranslatedSlugs($category),
                'icon' => $category->icon ? url('storage/' . $category->icon) : null,
            ];
        });
    }


    public function importantLinks(){
        $importantLinks = ImportantLink::status()->order()->get();
        return ImportantLinkResource::collection($importantLinks);
    }


    public function educationalregions(){
        $educationalRegions = EducationalRegion::status()->order()->get();
        return ImportantLinkResource::collection($educationalRegions);
    }

    public function partners(){
        $partners = Partner::status()->order()->get();
        return ImportantLinkResource::collection($partners);
    }


    public function textbooks(){
        $textBooks = Textbook::status()->order()->limit(2)->get();
        $siteSetting = SiteSetting::first();

        return response()->json([
            "data"=>$textBooks->map(function($item){
                return [
                    "id"=>$item->id,
                    "title"=>$item->title,
                    "subtitle"=>$item->subtitle,
                    "image"=>url('storage/'.$item->image),
                    'slug'=>$this->getTranslatedSlugs($item)
                ];
            }),
            "whatsapp_textbook_number"=>$siteSetting->whatsapp_textbook_number
        ]);
        return TextBookResource::collection($textBooks);
    }

    public function advertisements(){
        $advertisements = Advertisement::status()->order()->limit(2)->get();
        return AdvertisementResource::collection($advertisements);
    }

     public function blogs(){
        $blogs = BlogNew::status()->order()->limit(value: 4)->get();
        return BlogAndNewsResource::collection($blogs);
    }

    
}