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
use App\Models\HaveQuestion;
use App\Models\Language;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class HaveQuestionController extends Controller
{

    public function store(Request $request){
        $this->validate($request, [
            'name'=>'required',
            'surname'=>'required',
            'phone'=>'required',
            'email'=>'required',
            'note'=>'required',
        ]);

        HaveQuestion::create($request->except('_token'));

        return $this->responseMessage('success','Uğurla sorğu göndərildi',[], 200, null);
    }
}
