<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Http\Resources\Products\CardResource;
use App\Http\Resources\Products\ProductResource;
use App\Services\Api\Products\UserCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCardController extends Controller
{
    private $cardService;

    public function __construct(UserCardService $cardService)
    {
        $this->cardService = $cardService;
    }

    public function addToCards(Request $request)
    {
        try {
            $validatedData = $this->validateCardRequest($request);

            $result = $this->cardService->addToCards(
                Auth::id(),
                $validatedData['products']
            );

            return $this->responseMessage('success', 'Məhsullar uğurla səbətə əlavə olundu', $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function getCards()
    {
        try {
            $cards = $this->cardService->getCards(Auth::id());
            $cardsResource = CardResource::collection($cards);
            return $cardsResource->additional([
                'total' => CardResource::additionalData(request(), $cards)
            ]);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    private function validateCardRequest(Request $request): array
    {
        return $request->validate([
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id|integer',
            'products.*.quantity' => 'required|integer|min:1'
        ]);
    }


    public function removeCardProduct(Request $request)
    {
        try {
            $validatedData = $this->validateRemoveCardRequest($request);
            $this->cardService->removeCardProduct(Auth::id(), $validatedData['product_id']);
            return $this->responseMessage('success', 'Məhsul səbətdən silindi', $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }


    public function updateCardQuantityIncrease(Request $request)
    {
        try {
            $validatedData = $this->validateUpdateQuantityRequestIncrease($request);

            $this->cardService->updateCardQuantityIncrease(
                Auth::id(),
                $validatedData['product_id']
            );

            return $this->responseMessage('success', 'Məhsul sayı uğurla yeniləndi', $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }
    public function updateCardQuantityDecrease(Request $request)
    {
        try {
            $validatedData = $this->validateUpdateQuantityRequestIncrease($request);

            $this->cardService->updateCardQuantityDecrease(
                Auth::id(),
                $validatedData['product_id']
            );

            return $this->responseMessage('success', 'Məhsul sayı uğurla yeniləndi', $this->getCards(), 200, null);
        } catch (\Exception $e) {
            return $this->responseMessage('error', $e->getMessage(), null, 400, null);
        }
    }

    private function validateUpdateQuantityRequestIncrease(Request $request): array
    {
        return $request->validate([
            'product_id' => 'required|exists:user_cards,product_id|integer',
        ]);
    }

    private function validateRemoveCardRequest(Request $request): array
    {
        return $request->validate([
            'product_id' => 'required|exists:user_cards,product_id|integer'
        ]);
    }
}
