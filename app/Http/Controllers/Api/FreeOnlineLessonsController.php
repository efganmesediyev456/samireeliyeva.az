<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CancelOrderResource;
use App\Http\Resources\CityResource;
use App\Http\Resources\FreeOnlineLessonResource;
use App\Models\City;
use App\Models\FreeOnlineLesson;
use App\Models\OrderCancellationReason;
use App\Models\SiteSetting;
use App\Http\Resources\SiteSettingResource;

class FreeOnlineLessonsController extends Controller {
    public function index(){
        $frees = FreeOnlineLesson::status()->order()->get();
        return  FreeOnlineLessonResource::collection($frees);
    }
}
