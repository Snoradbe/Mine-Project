<?php

namespace App\Models;

use App\Models\LuckPerms\Player;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\VerifyEmail;
use App\Services\Avatar;
use App\Services\Groups\Group;
use App\Services\Player\UserGroup;
use App\Services\Player\UUIDGenerator;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $playername
 * @property string|null $email
 * @property string $password
 * @property string $ip_address
 * @property string $last_ip
 * @property \DateTimeImmutable $lastlogin
 * @property \DateTimeImmutable $regdate
 * @property int $email_verify
 * @property string|null $g2fa_details
 * @property Player $player
 * @property PlayTime $playtime
 * @property Coins $coins
 * @property Collection $guardKeys
 *
 * @method static Builder findByName(string $name, bool $byLike = false)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * @inheritDoc
     */
    protected $table = 'accounts';

    /**
     * @inheritDoc
     */
    protected $attributes = [
        'email' => '',
        'ip_address' => '',
        'last_ip' => '',
        'lastlogin' => '1970-12-31 00:00:00',
        'regdate' => '',
        'email_verify' => 0,
        '2fa_details' => null,
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'playername', 'email', 'password',
    ];

    /**
     * @inheritDoc
     */
    protected $hidden = [
        'password'/*, 'remember_token',*/
    ];

    /**
     * @inheritDoc
     */
    protected $casts = [
        /*'email_verified_at' => 'datetime',*/
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * @inheritDoc
     */
    protected $dates = [
        'lastlogin', 'regdate'
    ];

    /**
     * @inheritDoc
     */
    protected $with = [
        'player'
    ];

    /**
     * UUID пользователя.
     * Получать только из метода getUUID().
     *
     * @var string
     */
    private $uuid;

    /**
     * @inheritDoc
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function (User $model) {
            $model->regdate = $model->freshTimestamp();
        });
    }

    /**
     * Получить модель прав игрока по отношению один к одному.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function player()
    {
        return $this->hasOne(Player::class, 'uuid', 'uuid');
    }

    /**
     * Получить модель монет игрока по отношению один к одному.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function coins()
    {
        return $this->hasOne(Coins::class, 'playername', 'playername');
    }


    /**
     * Получить модель плейтайма игрока по отношению один к одному.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function playTime()
    {
        return $this->hasOne(PlayTime::class, 'username', 'playername');
    }

    /**
     * Получить коллекцию моделей секретных ключей по отношению один ко многим.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function guardKeys()
    {
        return $this->hasMany(GuardKey::class);
    }

    /**
     * @inheritDoc
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verify != 0;
    }

    /**
     * @inheritDoc
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verify' => $this->freshTimestamp()->getTimestamp(),
        ])->save();
    }

    /**
     * @inheritDoc
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail($this->hasEmail()));
    }

    /**
     * @inheritDoc
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * @inheritDoc
     */
    public function setRememberToken($value)
    {
        //do nothing
    }

    /**
     * Получить uuid пользователя как атрибут.
     *
     * @return string
     */
    public function getUuidAttribute(): string
    {
        return $this->getUUID();
    }

    /**
     * Получить значение колонки 2fa_details.
     * Из-за цифры в начале названия приходится извращаться.
     *
     * @return string|null
     */
    public function getG2faDetailsAttribute(): ?string
    {
        return $this->attributes['2fa_details'];
    }

    /**
     * Установить значение колонки 2fa_details.
     * Из-за цифры в начале названия приходится извращаться.
     *
     * @param string|null $secret
     */
    public function setG2faDetailsAttribute(?string $secret): void
    {
        $this->attributes['2fa_details'] = $secret;
    }

    /**
     * Найти пользователей по нику.
     *
     * @param Builder $query
     * @param string $name
     * @param bool $byLike
     * @return Builder
     */
    public function scopeFindByName(Builder $query, string $name, bool $byLike = false): Builder
    {
        if ($byLike) {
            $name = "%$name%";
        }

        return $query->where('playername', $name);
    }

    /**
     * Получить uuid пользователя.
     *
     * @return string
     */
    public function getUUID(): string
    {
        if (is_null($this->uuid)) {
            $this->uuid = UUIDGenerator::generate($this->playername);
        }

        return $this->uuid;
    }

    /**
     * Проверить, есть ли указанные права у пользователя.
     *
     * @var string|string[] $permissions
     * @var Server|null $server
     * @return bool
     */
    public function hasPermission($permissions, ?Server $server = null): bool
    {
        return true;
    }

    /**
     * Проверить, привязал ли игрок почту к аккаунту.
     *
     * @return bool
     */
    public function hasEmail(): bool
    {
        return !empty($this->email);
    }

    /**
     * Проверить, включена ли двухфактоная авторизация.
     *
     * @return bool
     */
    public function has2fa(): bool
    {
        return !empty($this->g2fa_details);
    }

    /**
     * Получить все группы пользователя.
     *
     * @return Collection
     */
    public function getGroups(): Collection
    {
        return $this->player->getGroups();
    }

    /**
     * Получить только основную группу пользователя.
     *
     * @var bool $onlyNotStaff если true, то будет получена игровая (не админская) группа, иначе любая
     * @return UserGroup
     */
    public function getPrimaryGroup(bool $onlyNotStaff = false): UserGroup
    {
        return $this->player->getPrimaryGroup(!$onlyNotStaff ? null : false);
    }

    /**
     * Получить только админскую группу пользователя.
     *
     * @return UserGroup|null
     */
    public function getStaffGroup(): ?UserGroup
    {
        return $this->player->getPrimaryGroup(true);
    }

    /**
     * Проверить, находится ли игрок в одной из указанных группах.
     *
     * @param array $groups
     * @return bool
     */
    public function inGroup(array $groups): bool
    {
        $userGroup = $this->getPrimaryGroup()->getGroup()->getName();

        return in_array($userGroup, $groups);
    }

    /**
     * Проверить, является ли игрок членом администрации.
     *
     * @return bool
     */
    public function inTeam(): bool
    {
        return $this->getPrimaryGroup()->getGroup()->isStaff();
    }

    /**
     * Проверить, является ли игрок админом.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return in_array($this->playername, config('admins', []));
        //return $this->getPrimaryGroup()->getGroup()->getRank() == Group::ADMIN_RANK;
    }

    /**
     * Получить количество монет игрока.
     *
     * @return int
     */
    public function getCoins(): int
    {
        return $this->coins->coins;
    }

    /**
     * Начислить игроку указанное количество монет.
     *
     * @param int $amount
     */
    public function depositCoins(int $amount): void
    {
        $this->coins->coins += $amount;
        $this->coins->save();
    }

    /**
     * Снять у игрока указанное количество монет.
     *
     * @param int $amount
     */
    public function withdrawCoins(int $amount): void
    {
        $this->coins->coins -= $amount;
        $this->coins->save();
    }

    /**
     * Установить игроку указанное количество монет.
     *
     * @param int $amount
     */
    public function setCoins(int $amount): void
    {
        $this->coins->coins = $amount;
        $this->coins->save();
    }

    /**
     * Получить путь к аватарке.
     * Например: uploads/avatars/name.png
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return Avatar::get($this->playername);
    }
}
