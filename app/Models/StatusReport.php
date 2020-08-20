<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property User $user
 * @property string $servername
 * @property string $type
 * @property \DateTime $created_at
 */
class StatusReport extends Model
{
    protected $fillable = [
        'user_id', 'servername', 'type'
    ];

    public $timestamps = false;

    protected $dates = ['created_at'];

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
}
