<?php


namespace App\Models\LuckPerms;


use App\Services\Player\UserGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property string $username
 * @property string $primary_group
 * @property Collection $permissions
 */
class Player extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'servers';

    /**
     * @inheritDoc
     */
    protected $table = 'luckperms_players';

    /**
     * @inheritDoc
     */
    protected $primaryKey = 'uuid';

    /**
     * @inheritDoc
     */
    public $incrementing = false;

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'uuid', 'username', 'primary_group'
    ];

    /**
     * Группы игрока.
     * @see UserGroup
     *
     * @var Collection
     */
    private $groups;

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * Получить список прав игрока по отношению один ко многим.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany(PlayerPermission::class, 'uuid', 'uuid');
    }

    /**
     * Получить группы игрока.
     *
     * @return Collection
     */
    public function getGroups(): Collection
    {
        if (is_null($this->groups)) {
            $groups = new Collection();
            $hasDefault = false;

            /* @var PlayerPermission $permission */
            foreach ($this->permissions as $permission)
            {
                if (Str::startsWith($permission->permission, 'group.')) {
                    $group = UserGroup::createFromPermission($permission);
                    $groups->add($group);

                    if ($group->getGroup()->isDefault()) {
                        $hasDefault = true;
                    }
                }
            }

            //если по каким-либо причинам группы у игрока не будут найдены
            //либо если у игрока только админские группы
            //создадим дефолтную группу
            if (!$hasDefault || $groups->isEmpty()) {
                $groups->add(UserGroup::createDefault());
            }

            $this->groups = $groups;
        }

        return $this->groups;
    }

    /**
     * Получить основную группу.
     *
     * @var bool|null $isStaff если null, то будет выбрана любая наибольшая группа, иначе по условию
     * @return UserGroup|null null возвращается только в случаях поиска админской группы
     */
    public function getPrimaryGroup(?bool $isStaff = null): ?UserGroup
    {
        $sorted = $this->getGroups()->sort(function (UserGroup $userGroup1, UserGroup $userGroup2) {
            return $userGroup1->getGroup()->getRank() < $userGroup2->getGroup()->getRank();
        });

        if (is_null($isStaff)) {
            return $sorted->first();
        }

        return $sorted->filter(function (UserGroup $userGroup) use ($isStaff) {
            return ($isStaff && $userGroup->getGroup()->isStaff()) || (!$isStaff && !$userGroup->getGroup()->isStaff());
        })->first();
    }
}
