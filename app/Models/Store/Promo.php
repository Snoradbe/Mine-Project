<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $code
 * @property int $discount
 * @method static Builder findByCode(string $code)
 */
class Promo extends Model
{
    protected $table = 'store_promos';

    protected $fillable = [
        'code', 'discount'
    ];

    public $timestamps = false;

    public function scopeFindByCode(Builder $query, string $code): Builder
    {
        return $query->where('code', $code)->take(1);
    }
}
