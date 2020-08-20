<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \DateTimeImmutable $created_at
 *
 * @method static Builder findByToken(string $token)
 */
class EmailToken extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = [
        'email', 'token'
    ];

    /**
     * @inheritDoc
     */
    protected $dates = [
        'created_at'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function (Model $model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

    /**
     * Найти по токену.
     *
     * @param Builder $query
     * @param string $token
     * @return Builder
     */
    public function scopeFindByToken(Builder $query, string $token): Builder
    {
        return $query->where('token', $token);
    }
}
