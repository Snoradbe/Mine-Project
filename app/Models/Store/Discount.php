<?php

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $name_en
 * @property int $discount
 * @property int $count_uses
 * @property \DateTime $date_start
 * @property \DateTime $date_end
 * @method static Builder isActive(bool $active = true)
 */
class Discount extends Model
{
    /**
     * @inheritDoc
     */
    protected $table = 'store_discounts';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'name', 'name_en', 'discount', 'date_start', 'date_end'
    ];

    /**
     * @inheritDoc
     */
    protected $attributes = [
        'count_uses' => 0
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    public function scopeIsActive(Builder $query, bool $active = true): Builder
    {
        if (!$active) {
            return $query;
        }

        $now = $this->freshTimestamp();
        return $query->where('date_start', '<', $now)
                ->where('date_end', '>', $now);
    }

    public function isActiveNow(): bool
    {
        $now = $this->freshTimestamp();
        return $this->date_start->getTimestamp() < $now->getTimestamp()
            && $this->date_end->getTimestamp() > $now->getTimestamp();
    }
}
