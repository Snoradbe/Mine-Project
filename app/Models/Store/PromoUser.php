<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property User $user
 * @property Promo $promo
 * @property \DateTime $created_at
 * @method static Builder findByUserAndPromo(User $user, Promo $promo)
 */
class PromoUser extends Model
{
    protected $table = 'store_promo_users';

    protected $fillable = [
        'promo_id', 'user_id'
    ];

    protected $dates = [
        'created_at'
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

    public function promo()
    {
        return $this->belongsTo(Promo::class);
    }

    public function scopeFindByUserAndPromo(Builder $query, User $user, Promo $promo): Builder
    {
        return $query->where('user_id', $user->id)
            ->where('promo_id', $promo->id)
            ->take(1);
    }
}
