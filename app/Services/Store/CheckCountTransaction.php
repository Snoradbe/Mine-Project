<?php


namespace App\Services\Store;


use App\Models\Store\Purchase;
use App\Models\User;
use Illuminate\Support\Carbon;

class CheckCountTransaction
{
    public function check(User $user): bool
    {
        if (!$user->getPrimaryGroup()->getGroup()->isDefault()) {
            return true;
        }

        $now = Carbon::now();
        $hour = $now->format('Y-m-d H:i:s');
        $now->subHour();
        $subHour = $now->format('Y-m-d H:i:s');

        $count = Purchase::where('user_id', $user->id)
            ->where('currency', 'coins')
            ->whereNotNull('completed_at')
            ->where('completed_at', '>=', $subHour)
            ->where('completed_at', '<=', $hour)
            ->count();

        return $count < config('site.store.count_transactions', 10);
    }
}
