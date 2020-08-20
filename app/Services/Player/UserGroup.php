<?php


namespace App\Services\Player;


use App\Models\LuckPerms\PlayerPermission;
use App\Services\Groups\Group;
use App\Services\Groups\GroupsManager;

class UserGroup
{
    /**
     * Объект группы.
     *
     * @var Group
     */
    protected $group;

    /**
     * Название сервера.
     *
     * @var string
     */
    protected $server;

    /**
     * Время истечения срока группы.
     *
     * @var int
     */
    protected $expiry;

    /**
     * Модель группы.
     *
     * @var PlayerPermission|null
     */
    protected $permissionGroup;

    /**
     * UserGroup constructor.
     *
     * @param PlayerPermission|null $playerPermission
     * @param Group $group
     * @param string $server
     * @param int $expiry
     */
    public function __construct(?PlayerPermission $playerPermission, Group $group, string $server, int $expiry)
    {
        $this->permissionGroup = $playerPermission;
        $this->group = $group;
        $this->server = $server;
        $this->expiry = $expiry;
    }

    /**
     * Получить объект группы.
     *
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * Получить название сервера.
     *
     * @return string
     */
    public function getServer(): string
    {
        return $this->server;
    }

    /**
     * Получить время истечения.
     *
     * @return int
     */
    public function getExpiry(): int
    {
        return $this->expiry;
    }

    /**
     * Добавить время истечения группы.
     *
     * @param int $seconds
     */
    public function addExpiry(int $seconds): void
    {
        if ($this->expiry == 0) {
            $this->setExpiry(time() + $seconds);
            return;
        }

        $this->permissionGroup->expiry += $seconds;
        $this->expiry = $this->permissionGroup->expiry;
    }

    /**
     * Установить время истечения группы.
     *
     * @param int $time
     */
    public function setExpiry(int $time): void
    {
        $this->permissionGroup->expiry = $time;
        $this->expiry = $time;
    }

    /**
     * Получить модель группы.
     *
     * @return PlayerPermission|null
     */
    public function getPermissionGroup(): ?PlayerPermission
    {
        return $this->permissionGroup;
    }

    /**
     * Создать из прав.
     *
     * @param PlayerPermission $permission
     * @return UserGroup
     */
    public static function createFromPermission(PlayerPermission $permission): self
    {
        return new self(
            $permission,
            GroupsManager::getGroupByName(str_replace('group.', '', $permission->permission)),
            $permission->server,
            $permission->expiry
        );
    }

    /**
     * Создать дефолтную группу.
     * Это нужно лишь для случаев, если группы у игрока вообще не найдены по каким-либо причинам.
     *
     * @return UserGroup
     */
    public static function createDefault(): self
    {
        return new self(
            null,
            GroupsManager::getGroupByName('default'),
            'global',
            0
        );
    }
}
