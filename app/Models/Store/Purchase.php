<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property User $user
 * @property Collection $cart
 * @property int $cost
 * @property string $currency
 * @property string|null $promo
 * @property string $ip
 * @property \DateTime $created_at
 * @property \DateTime|null $completed_at
 * @method static Builder onlyCompleted(bool $completed = true)
 */
class Purchase extends Model
{
    protected $table = 'store_purchases';

    protected $fillable = [
        'user_id', 'cost', 'currency', 'ip', 'promo'
    ];

    protected $attributes = [
        'promo' => null
    ];

    protected $dates = [
        'created_at', 'completed_at'
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        self::creating(function (Model $model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function scopeOnlyCompleted(Builder $query, bool $completed = true): Builder
    {
        if ($completed) {
            return $query->whereNotNull('completed_at');
        }

        return $query->whereNull('completed_at');
    }
}
