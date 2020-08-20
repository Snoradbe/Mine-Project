<?php


namespace App\Services\Store\Distributors;


use App\Models\Store\Cart;
use App\Models\Store\Product;
use App\Models\User;
use App\Services\Groups\GroupsManager;
use Illuminate\Support\Carbon;

class GroupsDistributor implements Distributor
{
    /**
     * @param User $user
     * @param string $groupName
     * @param int $amount
     * @throws \Exception
     */
    private function giveGroup(User $user, string $groupName, int $amount): void
    {
        $group = GroupsManager::getGroupByName($groupName);

        $userPrimaryGroup = $user->getPrimaryGroup();
        if ($userPrimaryGroup->getGroup()->isStaff()) {
            return;
        }

        /*
         * Если нужно продлить группу
         */
        if ($userPrimaryGroup->getGroup()->getName() == $group->getName()) {
            $date = Carbon::createFromTimestamp($userPrimaryGroup->getExpiry())->addMonths($amount);
            $userPrimaryGroup->setExpiry($date->getTimestamp());
            $userPrimaryGroup->getPermissionGroup()->save();
            return;
        }

        /*
         * Иначе выдаем новую
         */
        $expiry = Carbon::now()->addMonths($amount);
        GroupsManager::setGroup($user, $group, $expiry);
    }

    /**
     * @inheritDoc
     */
    public function distribute(Cart $cart): void
    {
        $this->giveGroup($cart->user, $cart->product->data, $cart->amount);
    }

    /**
     * @inheritDoc
     */
    public function distributeProduct(User $user, Product $product, int $amount): void
    {
        $this->giveGroup($user, $product->data, $amount);
    }
}
