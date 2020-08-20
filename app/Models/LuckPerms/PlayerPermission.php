<?php


namespace App\Models\LuckPerms;


use App\Services\Groups\Group;
use App\Services\Groups\GroupsManager;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property int $id
 * @property string $uuid
 * @property string $permission
 * @property int $value
 * @property string $server
 * @property string $world
 * @property int $expiry
 * @property string $contexts
 *
 * @method static int getCountAdmins()
 * @method static mixed deleteGroup(string $uuid, string $group)
 */
class PlayerPermission extends Model
{
    /**
     * @inheritDoc
     */
    protected $connection = 'servers';

    /**
     * @inheritDoc
     */
    protected $table = 'luckperms_user_permissions';

    /**
     * @inheritDoc
     */
    protected $attributes = [
        'server' => 'global',
        'world' => 'global',
        'expiry' => 0,
        'value' => 1,
        'contexts' => '{}'
    ];

    /**
     * @inheritDoc
     */
    protected $fillable = [
        'uuid', 'permission', 'value', 'server', 'world', 'expiry', 'contexts'
    ];

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * Получить количество членов администрации.
     *
     * @param Builder $query
     * @return int
     */
    public function scopeGetCountAdmins(Builder $query): int
    {
        return Cache::remember('get_count_admins', 300, function () use ($query) {
            $adminGroups = GroupsManager::getAdminGroups();
            if ($adminGroups->isEmpty()) {
                return 0;
            }

            $searchGroups = $adminGroups->map(function (Group $group) {
                return 'group.' . $group->getName();
            })->toArray();

            return $query->whereIn('permission', $searchGroups)->count();
        });
    }

    /**
     * Удалить группу у игрока по ее названию.
     *
     * @param Builder $query
     * @param string $uuid
     * @param string $group
     * @return mixed
     */
    public function scopeDeleteGroup(Builder $query, string $uuid, string $group)
    {
        return $query->where('uuid', $uuid)
            ->where('permission', 'group.' . $group)
            ->delete();
    }
}
