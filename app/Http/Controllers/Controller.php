<?php

namespace App\Http\Controllers;

use App\Services\MainService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Services\NotificationService;


class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    public $notificationService;
     public $mainService;

    public function __construct()
    {
        $this->mainService = new MainService();
        $this->notificationService = new NotificationService();

    }
    protected function responseMessage($status, $message, $data = null, $statusCode = 200, $route = null)
    {
        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'route' => $route
        ], $statusCode);
    }


    public function getUserQuestions(){
        
    }
}
