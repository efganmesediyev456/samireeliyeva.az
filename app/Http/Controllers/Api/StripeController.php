<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SubCategoryPacketItem;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Checkout\Session;
use Stripe\Webhook;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\SubCategory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StripeController extends Controller
{
    public function createStripeCheckout(Request $request)
    {
        // Sorğunun validasiyası
        $request->validate([
            'subcategory_id' => 'required|exists:subcategories,id',
            'duration_month_id' => 'required',
        ], [
            'subcategory_id.exists'=>'Bir problem oldu, zəhmət olmasa daha sonra yenidən yoxlayın',
            'duration_month_id.exists'=>'Bir problem oldu, zəhmət olmasa daha sonra yenidən yoxlayın',
        ]);

        try {
            // İstifadəçinin alınması
            $user = Auth::guard('api')->user();
            if (!$user) {
                return response()->json(['message' => 'User not authenticated'], 401);
            }

            $packageItem = SubCategoryPacketItem::find($request->duration_month_id);
            if (!$packageItem) {
                return response()->json(['message' => 'Subcategory Paket is not found'], status: 404);
            }

            // Alt kateqoriyanın tapılması
            $subcategory = SubCategory::findOrFail($request->subcategory_id);
            if (!$subcategory) {
                return response()->json(['message' => 'SubCategory not found'], 404);
            }

            // Müddətə görə qiymətin hesablanması
            $basePrice = $packageItem->price;
            if($packageItem->discount_price){
                $basePrice=$packageItem->discount_price;
            }
            $durationMonths = (int) $packageItem->packet?->duration_months;
         
            
            // Uzun müddətli abunəliklər üçün endirim məntiqini əlavə edə bilərsiniz
            $discountMultipliers = [
                3 => 1.0, // 3 ay üçün endirim yoxdur
                6 => 0.95, // 6 ay üçün 5% endirim
                9 => 0.9, // 9 ay üçün 10% endirim
                12 => 0.85, // 12 ay üçün 15% endirim
            ];
            
            // $priceMultiplier = $discountMultipliers[$durationMonths] ?? 1.0;
            // $totalPrice = $basePrice * $durationMonths * $priceMultiplier ;
            $totalPrice = $basePrice;

            Stripe::setApiKey(env('STRIPE_SECRET'));

            // Verilənlər bazası tranzaksiyasını başlat
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'subcategory_id' => $subcategory->id,
                'duration_months' => $durationMonths,
                'amount' => $totalPrice,
                'currency' => 'usd',
                'status' => 'pending',
                'package_item_id'=>$request->duration_month_id
            ]);

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => $subcategory->name . ' - ' . $durationMonths . ' Months',
                                'description' => $subcategory->description ?? 'Subscription for ' . $durationMonths . ' months',
                            ],
                            'unit_amount' => round($totalPrice * 100), // Sentlərə çevirme
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => url(env('ORDER_SUCCESS_URL').'?session_id={CHECKOUT_SESSION_ID}'),
                'cancel_url' => url(env('ORDER_CANCEL_URL').'?session_id={CHECKOUT_SESSION_ID}'),
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => $user->id,
                    'subcategory_id' => $subcategory->id,
                    'duration_months' => $durationMonths,
                ],
            ]);

            // Sifarişin Stripe sessiya ID ilə yenilənməsi
            $order->stripe_session_id = $session->id;
            $order->save();

            DB::commit();

            Log::info('Stripe Checkout Session Created', [
                'user_id' => $user->id,
                'order_id' => $order->id,
                'session_id' => $session->id,
                'amount' => $totalPrice,
                'duration' => $durationMonths . ' months'
            ]);

            return $this->responseMessage('success','Order is successfully created',[
                'order_id' => $order->id,
                'url' => $session->url,
                'session_id' => $session->id
            ], 200, null);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::info('Stripe Checkout message', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'message' => 'Payment session creation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function handleWebhook(Request $request)
    {
        Log::info('Stripe Webhook Received', ['headers' => $request->header()]);
        
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = env('STRIPE_WEBHOOK_SECRET');

        try {
            // Webhook imzasının yoxlanılması
            $event = Webhook::constructEvent($payload, $sigHeader, $webhookSecret);

            
            Log::info('Stripe Webhook Event', [
                'type' => $event->type,
                'id' => $event->id
            ]);
            
            
            // Müxtəlif hadisə növlərinin idarə edilməsi
            switch ($event->type) {
                case 'checkout.session.completed':
                    return $this->handleCheckoutSessionCompleted($event->data->object);
                
                case 'payment_intent.succeeded':
                    return $this->handlePaymentIntentSucceeded($event->data->object);
                
                case 'payment_intent.payment_failed':
                    return $this->handlePaymentIntentFailed($event->data->object);
                
                default:
                    Log::info('Unhandled Stripe event type: ' . $event->type);
            }
            
            return response()->json(['status' => 'success']);
        } catch (\UnexpectedValueException $e) {
            Log::info('Invalid Stripe webhook payload', [
                'message' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Invalid payload'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::info('Invalid Stripe webhook signature', [
                'message' => $e->getMessage()
            ]);
            return response()->json(['message' => 'Invalid  salam signature'], 400);
        } catch (\Exception $e) {
            Log::info('Stripe webhook message', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Webhook processing message'], 500);
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Checkout Session Completed', [
            'session_id' => $session->id,
            'payment_status' => $session->payment_status,
            'metadata' => $session->metadata
        ]);
        
        $orderId = $session->metadata->order_id ?? null;

        if (!$orderId) {
            Log::info('No order ID in session metadata', ['session_id' => $session->id]);
            return response()->json(['message' => 'Order ID not found'], 400);
        }       
        $order = Order::find($orderId);
        if (!$order) {
            Log::info('Order not found', ['order_id' => $orderId]);
            return response()->json(['message' => 'Order not found'], 404);
        }

       
        
        if ($session->payment_status === 'paid') {
            DB::beginTransaction();
            try {
                // Sifariş statusunun yenilənməsi
                $order->status = 'completed';
                $order->stripe_payment_intent_id = $session->payment_intent;
                
                // Bitmə tarixinin hesablanması
                $expiresAt = Carbon::now()->addMonths($order->duration_months);
                $order->expires_at = $expiresAt;
                
                $order->save();
                
                // Burada abunəliyi aktivləşdirmək və ya giriş vermək üçün əlavə məntiq əlavə edə bilərsiniz
                // Məsələn, user_subcategory qeydi yarada bilər və ya istifadəçi icazələrini yeniləyə bilərsiniz
                
                DB::commit();
                
                Log::info('Order completed successfully', [
                    'order_id' => $order->id,
                    'expires_at' => $expiresAt->toDateTimeString()
                ]);
                
                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                DB::rollBack();
                
                Log::info('message processing completed checkout', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json(['message' => 'Order processing failed'], 500);
            }
        }
        
        Log::warning('Checkout session not paid', [
            'session_id' => $session->id,
            'payment_status' => $session->payment_status
        ]);
        
        return response()->json(['status' => 'pending']);
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment Intent Succeeded', [
            'payment_intent_id' => $paymentIntent->id,
            'amount' => $paymentIntent->amount / 100, // Sentlərdən çevirmə
            'currency' => $paymentIntent->currency
        ]);
        
        // Ödəmə intent ID ilə sifarişi tapmaq
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            Log::info('Found order for payment intent', ['order_id' => $order->id]);
            
            // Əgər sifariş statusu hələ də gözləyirsə, onu yeniləyin
            if ($order->status === 'pending') {
                $order->status = 'completed';
                
                // Bitmə tarixi təyin edilməyibsə onu təyin edin
                if (!$order->expires_at) {
                    $order->expires_at = Carbon::now()->addMonths($order->duration_months);
                }
                
                $order->save();
                
                Log::info('Updated order status to completed', ['order_id' => $order->id]);
            }
        } else {
            Log::warning('No order found for payment intent', ['payment_intent_id' => $paymentIntent->id]);
        }
        
        return response()->json(['status' => 'success']);
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        Log::warning('Payment Intent Failed', [
            'payment_intent_id' => $paymentIntent->id,
            'message' => $paymentIntent->last_payment_message
        ]);
        
        $order = Order::where('stripe_payment_intent_id', $paymentIntent->id)->first();
        
        if ($order) {
            $order->status = 'failed';
            $order->save();
            
            Log::info('Updated order status to failed', ['order_id' => $order->id]);
        } else {
            Log::warning('No order found for failed payment intent', ['payment_intent_id' => $paymentIntent->id]);
        }
        
        return response()->json(['status' => 'payment_failed']);
    }

    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string'
        ]);
        
        $sessionId = $request->session_id;
        
        $order = Order::where('stripe_session_id', $sessionId)->first();
        
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        
        return response()->json([
            'order_id' => $order->id,
            'status' => $order->status,
            'subcategory' => $order->subcategory?->name,
            'duration_months' => $order->duration_months,
            'amount' => $order->amount,
            'expires_at' => $order->expires_at ? $order->expires_at->toDateTimeString() : null
        ]);
    }
}