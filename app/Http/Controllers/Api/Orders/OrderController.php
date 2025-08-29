<?php

namespace App\Http\Controllers\Api\Orders;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\DeliveryAddress;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function placeOrder(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'delivery_type' => 'required|string|in:home_delivery,store_pickup',
            'cart_items' => 'required|array',
            'cart_items.*.product_id' => 'required|exists:products,id',
            'cart_items.*.quantity' => 'required|integer|min:1',
            'cart_items.*.price' => 'required|numeric|min:0',
        ]);
        if ($request->delivery_type == 'home_delivery') {
            $deliveryValidator = Validator::make($request->all(), [
                'city' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'additional_info' => 'nullable|string|max:1000',
            ]);
            if ($deliveryValidator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $deliveryValidator->errors()->first(),
                    'errors' => $deliveryValidator->errors()
                ], 422);
            }
        } else { // store_pickup
            $pickupValidator = Validator::make($request->all(), [
                'store' => 'required',
            ]);

            if ($pickupValidator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation error',
                    'errors' => $pickupValidator->errors()
                ], 422);
            }
        }

        // Calculate the total cart value
        $cartTotal = 0;
        foreach ($request->cart_items as $item) {
            $cartTotal += $item['price'] * $item['quantity'];
        }

        try {
            // Begin transaction
            \DB::beginTransaction();

            // Create order
            $order = new Order();
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->phone = $request->phone;
            $order->delivery_type = $request->delivery_type;
            $order->total_amount = $cartTotal;
            $order->user_id = Auth::user()->id;
            $order->save();

            $order->status()->create([
                "status"=>OrderStatusEnum::PENDING
            ]);

            // Add delivery address or store pickup details
            if ($request->delivery_type == 'home_delivery') {
                $deliveryAddress = new DeliveryAddress();
                $deliveryAddress->order_id = $order->id;
                $deliveryAddress->city = $request->city;
                $deliveryAddress->address = $request->address;
                $deliveryAddress->additional_info = $request->additional_info ?? null;
                $deliveryAddress->save();
            } else {
                $store = new Store();
                $store->address = $request->store;
                $store->order_id = $order->id;
                $store->save();
            }

            // Save order items
            foreach ($request->cart_items as $item) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->product_id = $item['product_id'];
                $orderItem->quantity = $item['quantity'];
                $orderItem->price = $item['price'];
                $orderItem->total = $item['price'] * $item['quantity'];
                $orderItem->save();
            }

            \DB::commit();

            // Prepare response
            $responseData = [
                'status' => 'success',
                'message' => 'Order placed successfully',
                'order' => [
                    'id' => $order->id,
                    'first_name' => $order->first_name,
                    'last_name' => $order->last_name,
                    'email' => $order->email,
                    'phone' => $order->phone,
                    'delivery_type' => $order->delivery_type,
                    'total_amount' => $order->total_amount,
                    'items' => $order->items,
                ]
            ];

            // Add delivery-specific details to response
            if ($order->delivery_type == 'home_delivery') {
                $responseData['order']['delivery_address'] = [
                    'city' => $deliveryAddress->city,
                    'address' => $deliveryAddress->address,
                    'additional_info' => $deliveryAddress->additional_info,
                ];
            } else {
                $responseData['order']['store'] = [
                    'id' => $store->id,
                    'address' => $store->address,
                ];
            }

            return response()->json($responseData, 201);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to place order',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function cancelOrder(Request $request){
        $this->validate($request, [
            'order_id' => 'required|exists:orders,id',
            'reason_id' => 'required|exists:order_cancellation_reasons,id',
            'reason' => 'sometimes|max:5000',
        ]);
        try{
            $order = Order::find($request->order_id);
            $order->status()->delete();
            $order->status()->create([
                "status"=>OrderStatusEnum::CANCELED->value,
                "reason_id" => $request->reason_id,
                "reason" => $request->reason
            ]);

            return $this->responseMessage('success','Uğurlu əməliyyat',null, 200);
        }catch (\Exception $e){
            return $this->responseMessage('error',$e->getMessage(),null, 400);
        }

    }
}
