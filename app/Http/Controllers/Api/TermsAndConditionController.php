<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TextBookResource;
use App\Models\TermsAndCondition;
use App\Models\Textbook;
use Illuminate\Http\Request;
use App\Http\Resources\TermsAndConditionResource;

class TermsAndConditionController extends Controller
{
    public function index(Request $request)
    {
        $item = TermsAndCondition::first();
        return new TermsAndConditionResource($item);
    }

}
