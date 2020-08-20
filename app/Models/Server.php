<?php

namespace App\Models;

use App\Helpers\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * @property string $name
 * @property string $type
 * @property string $core
 * @property string $maintenance
 * @property string $whitelist
 * @property int $players
 * @property int $slots
 * @property string $last_response
 *
 * @method static Builder onlyGame()
 */
class Server extends Model
{
    /**
     * Игровой тип сервера.
     * Выводится пользователям.
     */
    public const TYPE_GAME = 'Game';

    /**
     * НЕ игровой тип сервера.
     * Например лобби.
     */
    public const TYPE_SERVICE = 'Service';

    /**
     * НЕ игровой тип сервера.
     * Например авторизация.
     * Используется только в status.mine.org
     */
    public const TYPE_SERVICE_PRIMARY = 'Service-Primary';

    /**
     * НЕ игровой тип сервера.
     * Например лобби.
     * Используется только в status.mine.org
     */
    public const TYPE_SERVICE_SECONDARY = 'Service-Secondary';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'name';

    /**
     * @inheritDoc
     */
    public $incrementing = false;

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'core', 'maintenance', 'whitelist', 'last_response'
    ];

    /**
     * Добавить в запрос только игровые сервера, которые выводятся для пользователей.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOnlyGame(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_GAME);
    }

    /**
     * Узнать, работает ли сервер.
     *
     * @return bool
     */
    public function isOnline(): bool
    {
        return $this->maintenance != 'true';
    }

    /**
     * Получить аббревиатуру названия сервера.
     * Например: SlighTech -> ST
     *
     * @return string
     */
    public function getAbbr(): string
    {
        return Str::substr(preg_replace('/[^A-Z]/u', '', $this->name), 0, 2);
    }

    /**
     * Получить список серверов из кэша.
     *
     * @param bool $onlyGame
     * @return Collection
     */
    public static function getAllFromCache(bool $onlyGame = true): Collection
    {
        /* @var Collection $servers */
        $servers =  Cache::remember('servers.' . ($onlyGame ? 'game' : 'all'), 300, function () use ($onlyGame) {
            return $onlyGame
                ? self::onlyGame()->get()
                : self::all();
        });

        return self::sort($servers);
    }

    public static function sort(Collection $servers)
    {
        $sorting = config('servers-sorting', []);
        return $servers->sort(function (Server $server1, Server $server2) use ($sorting) {
            $sort1 = $sorting[$server1->name] ?? 0;
            $sort2 = $sorting[$server2->name] ?? 0;
            return $sort1 < $sort2;
        })->values();
    }
}
