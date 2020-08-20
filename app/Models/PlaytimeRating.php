<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property User $user
 * @property PlayTime $playtime
 * @property string $username
 * @property int $global
 * @method static Builder byUser(string $username)
 * @method static Builder nearRanks(?string $server, int $rank, int $need = 1)
 */
class PlaytimeRating extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'username');
    }

    public function playtime()
    {
        return $this->belongsTo(PlayTime::class, 'username');
    }

    public function scopeByUser(Builder $query, string $username): Builder
    {
        return $query->where('username', $username)->take(1);
    }

    public function scopeNearRanks(Builder $query, ?string $server, int $rank, int $need = 1): Builder
    {
        $minRank = $rank - $need;
        $maxRank = $rank + $need;

        if (is_null($server)) {
            $server = 'global';
        }

        return $query->where($server, '>=', $minRank)
            ->where($server, '<=', $maxRank)
            ->where($server, '!=', $rank);
    }
}
