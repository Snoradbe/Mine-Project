<?php


namespace App\Services\Store\Distributors;


use App\Models\Store\Cart;
use App\Models\Store\Product;
use App\Models\User;

interface Distributor
{
    /**
     * @param Cart $cart
     */
    public function distribute(Cart $cart): void;

    /**
     * @param User $user
     * @param Product $product
     * @param int $amount
     */
    public function distributeProduct(User $user, Product $product, int $amount): void;
}
