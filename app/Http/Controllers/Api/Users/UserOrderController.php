<?php
namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\OrderResource;
use App\Http\Resources\Products\ProductResource;
use App\Models\UserFavorite;
use App\Models\Product;
use App\Repositories\UserFavoriteRepository;
use App\Services\Api\Products\UserFavoriteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserOrderController extends Controller
{



    public function getOrders()
    {
        try {
            $user = Auth::user();
            $orders = $user->orders()->paginate(10);
            return OrderResource::collection($orders);
        } catch (\Exception $e) {
            return $this->responseMessage('error',$e->getMessage(), null, 400, null);
        }
    }

}
