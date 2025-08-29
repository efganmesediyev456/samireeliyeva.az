<?php

namespace App\Repositories;

use App\Models\UserCard;
use App\Repositories\Contracts\UserCardRepositoryInterface;
use Illuminate\Support\Facades\DB;

class UserCardRepository implements UserCardRepositoryInterface
{
    protected $model;

    public function __construct(UserCard $model)
    {
        $this->model = $model;
    }

    public function findByUserAndProduct(int $userId, int $productId): ?UserCard
    {
        return $this->model->where([
            'user_id' => $userId,
            'product_id' => $productId
        ])->first();
    }

    public function create(array $data): UserCard
    {
        return $this->model->create($data);
    }

    public function getUserCards(int $userId)
    {
        return $this->model
            ->where('user_id', $userId)
            ->with('product')
            ->latest()
            ->get();
    }

    public function bulkAddToCards(int $userId, array $products): array
    {
        $results = [
            'added' => [],
            'updated' => [],
            'skipped' => []
        ];

        DB::beginTransaction();
        try {
            foreach ($products as $product) {
                $productId = $product['id'];
                $quantity = $product['quantity'];

                $existingCard = $this->model->where('user_id', $userId)
                    ->where('product_id', $productId)
                    ->first();

                if (!$existingCard) {
                    $this->create([
                        'user_id' => $userId,
                        'product_id' => $productId,
                        'quantity' => $quantity
                    ]);
                    $results['added'][] = $productId;
                } else {
                    $existingCard->update(['quantity' => $quantity]);
                    $results['updated'][] = $productId;
                }
            }

            DB::commit();
            return $results;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }



    public function removeCardProduct(int $userId, int $productId):void
    {
        $this->model->where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();
    }


    public function updateCardQuantityIncrease(int $userId, int $productId): void
    {
        $userCard = $this->model->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if (!$userCard) {
            throw new \Exception('Məhsul səbətdə tapılmadı');
        }

        $userCard->increment('quantity');
    }
    public function updateCardQuantityDecrease(int $userId, int $productId): void
    {
        $userCard = $this->model->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        $userCard->decrement('quantity');

        if($userCard->quantity<=0){
            $this->removeCardProduct($userId, $productId);
        }

        if (!$userCard) {
            throw new \Exception('Məhsul səbətdə tapılmadı');
        }

    }
}
