<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property User $user
 * @property string $playername
 * @property int $coins
 */
class Coins extends Model
{
    /**
     * @inheritDoc
     */
    protected $primaryKey = 'playername';

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'playername'
    ];

    /**
     * @inheritDoc
     */
    protected $attributes = [
        'coins' => 0
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    /*protected $with = [
        'user'
    ];*/

    /**
     * Получить модель пользователя по отношению один к одному.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class, 'playername', 'playername');
    }
}
