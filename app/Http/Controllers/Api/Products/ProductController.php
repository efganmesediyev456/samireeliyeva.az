<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Controller;
use App\Http\Resources\About\AboutResource;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\Products\ProductResource;
use App\Http\Resources\Users\UserResource;
use App\Models\About;
use App\Models\Language;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request){
        $products = Product::orderBy('id','desc');

        if($request->category_id and $request->category_id){
            $products = $products->where('category_id', $request->category_id);
        }

        if($request->property and is_array($request->property) and count($request->property)){
            $products = $products->whereHas('subProperties', function($query) use($request){
                $query->whereIn('sub_property_id', $request->property);
            });
        }
        if($request->min and $request->min > 0){
            $products = $products->where('price','>=',$request->min);
        }
        if($request->max and $request->max > 0){
            $products = $products->where('price','<=',$request->max);
        }

        $products = $products->paginate(12);
        return ProductResource::collection($products);
    }

    public function allProducts(){
        return ProductResource::collection(Product::orderBy('id','desc')->get());
    }

    public function product($slug){
        try {
            $item = Product::get()->filter(function($q) use($slug){
                return $q->slug == $slug;
            })->first();
            if(!$item){
                return $this->responseMessage('error', 'Dəyər tapılmadı',[], 400,null);
            }
            return new ProductResource($item);

        }catch (\Exception $exception){
            return $this->responseMessage('error',$exception->getMessage(), [], 500);
        }
    }

    public function single(Request $request, $slug){
       
        $product = Product::whereHas('translations', function($query)use($slug){
            return $query->where('value', $slug)->where('locale',app()->getLocale())->where('key','slug');
        })->first();
        if(is_null($product)){
            return $this->responseMessage("error",'No found product',null, 400);
        }
        return new ProductResource($product);
   
    }
}
