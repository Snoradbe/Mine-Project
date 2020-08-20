<?php

namespace App\Models;

use App\Helpers\Str;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property User $user
 * @property string $key
 *
 * @method static Builder findKey(User $user, string $key)
 * @method static Builder getAllByUser(User $user)
 * @method static mixed deleteAllFromUser(User $user)
 */
class GuardKey extends Model
{
    /**
     * @inheritDoc
     */
    protected $fillable = [
        'user_id', 'key'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * Получить модель пользователя по отношению.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Найти ключ пользователя.
     *
     * @param Builder $query
     * @param User $user
     * @param string $key
     * @return Builder
     */
    public function scopeFindKey(Builder $query, User $user, string $key): Builder
    {
        return $query->where('user_id', $user->id)
            ->where('key', $key);
    }

    /**
     * Получить все ключи пользователя.
     *
     * @param Builder $query
     * @param User $user
     * @return Builder
     */
    public function scopeGetAllByUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Удалить все ключи пользователя.
     *
     * @param Builder $query
     * @param User $user
     * @return mixed
     */
    public function scopeDeleteAllFromUser(Builder $query, User $user)
    {
        return $query->where('user_id', $user->id)
            ->delete();
    }

    /**
     * Сгенерировать и сохранить новый ключ пользователя.
     *
     * @param User $user
     * @return static
     */
    public static function generateNew(User $user): self
    {
        //$key = Str::random(8);
        $key = rand(10000000, 99999999);
        $self = new self(['user_id' => $user->id, 'key' => $key]);
        $self->save();

        return $self;
    }

    /**
     * Пересоздать ключи пользователя.
     *
     * @param User $user
     * @return Collection
     */
    public static function regenerateKeys(User $user): Collection
    {
        self::deleteAllFromUser($user);
        $keys = new Collection();

        for ($i = 0, $maxI = config('site.two_factor.count_keys', 5); $i < $maxI; $i++)
        {
            $keys->add(self::generateNew($user));
        }

        return $keys;
    }
}
