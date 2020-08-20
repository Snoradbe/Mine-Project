<?php


namespace App\Services\Store\Distributors;


use App\Models\Store\Cart;
use App\Models\Store\Product;
use App\Models\User;

class CoinsDistributor implements Distributor
{
    /**
     * @inheritDoc
     */
    public function distribute(Cart $cart): void
    {
        $amount = $cart->amount * $cart->product->amount;
        $cart->user->depositCoins($amount);
    }

    /**
     * @inheritDoc
     */
    public function distributeProduct(User $user, Product $product, int $amount): void
    {
        $user->depositCoins($amount * $product->amount);
    }
}
