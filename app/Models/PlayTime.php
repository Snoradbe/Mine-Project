<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * @property string $username
 * @property int $JustPvP
 * @property int $Event
 * @property int $SlighTech
 */
class PlayTime extends Model
{
    /**
     * @inheritDoc
     */
    protected $primaryKey = 'username';

    /**
     * @inheritDoc
     */
    public $incrementing = false;

    /**
     * @inheritDoc
     */
    protected $table = 'playtime_manager';

    /**
     * @inheritDoc
     */
    public $timestamps = false;

    /**
     * Список колонок с серверами в таблице.
     * Получать только через метод getColumnServers()
     *
     * @var string[]
     */
    private $columns;

    /**
     * Получить список колонок серверов.
     *
     * @return array
     */
    protected function getColumnServers(): array
    {
        if (is_null($this->columns)) {
            $this->columns = Server::getAllFromCache()->map(function (Server $server) {
                return $server->name;
            })->toArray();
        }

        return $this->columns;
    }

    /**
     * Получить сумму колонок серверов.
     *
     * @return string
     */
    protected function getSumColumnServers(): string
    {
        $columns = $this->getColumnServers();

        return '(' . implode('+', $columns) . ')';
    }

    public function getRatingUsers(?string $server = null): array
    {
        $servers = is_null($server) ? $this->getSumColumnServers() : "($server)";
        $sql = 'SELECT username, ' . $servers . ' as total, (SELECT COUNT(*)+1 FROM `playtime_manager` WHERE ' . $servers . ' > total) AS rank
                        FROM  `' . $this->table . '`
                        ORDER BY rank ASC';

        $results = $this->getConnection()->select($sql);
        if (!is_array($results) || empty($results)) {
            return [];
        }

        return $results;
    }

    /**
     * Обнулить время на сервере.
     * Если сервер не указан, то обнуление будет на всех серверах.
     *
     * @param string|null $server
     * @return $this
     */
    public function resetPlayTime(?string $server = null): self
    {
        $columns = $this->getColumnServers();

        if (!is_null($server)) {
            foreach ($columns as $column)
            {
                if ($column == $server) {
                    $this->{$column} = 0;
                    break;
                }
            }

            return $this;
        }

        foreach ($columns as $column)
        {
            $this->{$column} = 0;
        }

        return $this;
    }

    /**
     * Получить онлайн на конкретном сервере.
     *
     * @param string $server
     * @return int
     */
    public function getOnlineOnServer(string $server): int
    {
        return isset($this->attributes[$server]) ? $this->attributes[$server] : 0;
    }

    /**
     * Получить общий онлайн игрока на всех серверах.
     *
     * @return int
     */
    public function getTotalOnline(): int
    {
        $online = 0;

        $columns = $this->getColumnServers();
        foreach ($columns as $column)
        {
            $online += $this->{$column};
        }

        return $online;
    }

    /**
     * Получить позицию указанного игрока в топе.
     *
     * @param string|null $username
     * @param string|null $server
     * @return int
     */
    public function getRankPosition(?string $username = null, ?string $server = null): int
    {
        if (is_null($username)) {
            $username = $this->username;
        }

        return Cache::remember('rank_position.' . $username . '_s_' .$server, 300, function () use ($username, $server) {
            $servers = is_null($server) ? $this->getSumColumnServers() : "($server)";
            $sql = 'SELECT username, ' . $servers . ' as total, (SELECT COUNT(*)+1 FROM `playtime_manager` WHERE ' . $servers . ' > total) AS rank
                        FROM  `playtime_manager` x
                        WHERE x.username = :username LIMIT 1';

            $results = $this->getConnection()->select($sql, ['username' => $username]);
            if (!is_array($results) || empty($results) || !isset($results[0]->rank)) {
                return -1;
            }

            return (int) $results[0]->rank;
        });
    }

    /**
     * Получить сумму онлайна всех игроков.
     *
     * @return int
     */
    public function getAllTotalOnline(?string $username = null): int
    {
        return Cache::remember('all_total_online', 300, function () {
            $servers = $this->getSumColumnServers();

            $sql = 'SELECT sum(' . $servers . ') as total FROM `' . $this->table . '`';

            $results = $this->getConnection()->select($sql);
            if (!is_array($results) || empty($results) || !isset($results[0]->total)) {
                return -1;
            }

            return (int) $results[0]->total;
        });
    }
}
