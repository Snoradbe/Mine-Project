<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property User $user
 * @property Product $product
 * @property int $amount
 * @property Purchase|null $purchase
 * @property int|null $purchase_id
 * @method static Builder byUser(User $user)
 * @method static Builder onlyPurchased(bool $purchased = true)
 */
class Cart extends Model
{
    protected $table = 'store_cart';

    protected $fillable = [
        'user_id', 'product_id', 'amount'
    ];

    protected $attributes = [
        'purchase_id' => null
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function scopeByUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function scopeOnlyPurchased(Builder $query, bool $purchased = true): Builder
    {
        if ($purchased) {
            return $query->whereNotNull('purchase_id');
        }

        return $query->whereNull('purchase_id');
    }

    public function getPrice(): int
    {
        return $this->amount * $this->product->getPrice();
    }

    public function distribute(): void
    {
        app($this->product->category->distributor)->distribute($this);
    }
}
