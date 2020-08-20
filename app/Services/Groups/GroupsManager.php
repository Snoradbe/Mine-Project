<?php


namespace App\Services\Groups;


use App\Models\LuckPerms\PlayerPermission;
use App\Models\User;
use Illuminate\Support\Collection;

class GroupsManager
{
    /**
     * Коллекция групп.
     * @see Group
     *
     * @var Collection
     */
    private static $groups;

    /**
     * Получить коллекцию групп.
     * @see Group
     *
     * @return Collection
     */
    public static function getGroups(): Collection
    {
        if (is_null(self::$groups)) {
            self::$groups = collect(config('site.groups', []))->map(function (array $data) {
                return new Group($data);
            });
        }

        return self::$groups;
    }

    /**
     * Получить все админские группы.
     *
     * @return Collection
     */
    public static function getAdminGroups(): Collection
    {
        return self::getGroups()->filter(function (Group $group) {
            return $group->isStaff();
        });
    }

    /**
     * Получить все НЕ админские группы.
     *
     * @return Collection
     */
    public static function getNotAdminGroups(): Collection
    {
        return self::getGroups()->filter(function (Group $group) {
            return !$group->isStaff();
        });
    }

    /**
     * Получить все продаваемые группы.
     *
     * @return Collection
     */
    public static function getSellingGroups(): Collection
    {
        return self::getGroups()->filter(function (Group $group) {
            return $group->isSelling();
        });
    }

    /**
     * Получить группу по её названию.
     *
     * @param string $name
     * @return Group
     */
    public static function getGroupByName(string $name): Group
    {
        $group = self::getGroups()->filter(function (Group $group) use ($name) {
            return $group->getName() == $name;
        })->first();

        if (is_null($group)) {
            $group = Group::createEmptyGroup($name);
        }

        return $group;
    }

    /**
     * Установить группу пользователю.
     *
     * @param User $user
     * @param Group $group
     * @param \DateTime|null $expiry
     * @throws \Exception
     */
    public static function setGroup(User $user, Group $group, ?\DateTime $expiry)
    {
        $userGroup = $user->getPrimaryGroup();
        if (!$userGroup->getGroup()->isDefault()) {
            $userGroup->getPermissionGroup()->delete();
        } else {
            if (!is_null($userGroup->getPermissionGroup())) {
                $userGroup->getPermissionGroup()->delete();
            } else {
                PlayerPermission::deleteGroup($user->getUUID(), $userGroup->getGroup()->getName());
            }
        }

        $playerPermission = new PlayerPermission([
            'uuid' => $user->getUUID(),
            'permission' => 'group.' . $group->getName()
        ]);

        if (!is_null($expiry)) {
            $playerPermission->expiry = $expiry->getTimestamp();
        }

        $playerPermission->save();

        $user->player->primary_group = $group->getName();
        $user->player->save();
    }
}
