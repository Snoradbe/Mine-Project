<?php


namespace App\Services\Store\Distributors;


use App\Models\Store\Cart;
use App\Models\Store\MineStore;
use App\Models\Store\Product;
use App\Models\User;

class ItemsDistributor implements Distributor
{
    public function distribute(Cart $cart): void
    {
        $mineStore = new MineStore([
            'purchaseid' => $cart->purchase->id,
            'playername' => $cart->user->playername,
            'productid' => $cart->product->id,
            'amount' => $cart->amount,
            'servername' => $cart->product->servername
        ]);
        $mineStore->save();
    }

    /**
     * @inheritDoc
     */
    public function distributeProduct(User $user, Product $product, int $amount): void
    {
        $mineStore = new MineStore([
            'purchaseid' => '',
            'playername' => $user->playername,
            'productid' => $product->id,
            'amount' => $amount,
            'servername' => $product->servername
        ]);
        $mineStore->save();
    }
}
