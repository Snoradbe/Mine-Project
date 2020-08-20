<?php


namespace App\Console\Commands;


use App\Models\Server;
use App\Services\Game\MineQuery\Query;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

class StatusChart extends Command
{
    private const CHART_LIMIT = 300; //количество записываемых секунд в графике

    protected $signature = 'status:chart';

    protected $description = 'Subscribe to a Redis channel';

    private $servers;

    private $charts;

    private $disk;

    private function initServers(): void
    {
        $this->servers = Server::all()->map(function (Server $server) {
            return $server->name;
        });
    }

    private function initCharts(): void
    {
        $charts = [];
        foreach ($this->servers as $server)
        {
            try {
                $chart = json_decode($this->disk->get($this->getPathToChartJson($server)), true);
            } catch (FileNotFoundException $e) {
                $chart = [
                    'labels' => [],
                    'data' => []
                ];
            }
            if (($count = count($chart['data'])) > self::CHART_LIMIT) {
                array_splice($chart['data'], 0, $count - self::CHART_LIMIT);
                array_splice($chart['labels'], 0, $count - self::CHART_LIMIT);
            }
            $charts[$server] = [
                'total' => count($chart['data']),
                'chart' => $chart
            ];
        }

        $this->charts = $charts;
    }

    private function getPathToChartJson(string $server): string
    {
        return 'servers_statuses/' . $server . '_chart.json';
    }

    private function putToChart(string $server, $tps, $now): void
    {
        if ($tps > 20) {
            $tps = '20.0';
        }

        if (!isset($this->charts[$server])) {
            $this->charts[$server] = [
                'total' => 0,
                'chart' => [
                    'labels' => [],
                    'data' => []
                ]
            ];
        }

        if ($this->charts[$server]['total'] >= self::CHART_LIMIT) {
            array_splice($this->charts[$server]['chart']['labels'], 0, 1);
            array_splice($this->charts[$server]['chart']['data'], 0, 1);
        } else {
            $this->charts[$server]['total']++;
        }
        array_push($this->charts[$server]['chart']['labels'], $now);
        array_push($this->charts[$server]['chart']['data'], (float) $tps);
    }

    private function saveProxyData(): void
    {
        try {
            $query = new Query();
            $query->connect(explode(':', config('site.play_ip', 'play.mine.org'))[0], 25565, 2);
            $info = $query->getInfo();
            $status = $info !== false && !empty($info);
        } catch (\Exception $exception) {
            $status = false;
        }

        $this->disk->put('servers_statuses/_proxy.json', (int) $status);
    }

    public function handle()
    {
        print "Инициализация...\n";
        $this->disk = Storage::disk('local');
        print "Загрузка серверов...\n";
        $this->initServers();
        print "Загрузка графиков...\n";
        $this->initCharts();

        print "Подключаемся к redis...\n";
        $redis = Redis::connection('default');
        print "Соединение установлено\n";
        print "Скрипт запущен\n";

        $tick = 0;
        while (true) {
            //каждые 30 секунд обновляем статус прокси-сервера (bungee)
            if ($tick % 30 === 0) {
                try {
                    $this->saveProxyData();
                } catch (\Exception $exception) {
                    //do nothing
                }
            }

            //каждые 5 минут обновляем список серверов
            if ($tick == 300) {
                try {
                    $this->initServers();
                } catch (\Exception $exception) {
                    //do nothing
                }
                $tick = 0;
            }


            $now = date('H:i:s');
            foreach ($this->servers as $server)
            {
                try {
                    $data = $redis->hgetall($server);
                    if (!$data) {
                        $data = [
                            'name' => 'undefined',
                            'type' => 'undefined',
                            'core' => 'undefined',
                            'slots' => 0,
                            'maintenance' => false,
                            'uptime' => 0,
                            'curtps' => 0,
                            'players' => 0,
                            'last-response' => '1971-01-01 00:00:00',
                            'avgtps' => 0,
                        ];
                    }
                    $this->disk->put('servers_statuses/' . $server . '.json', json_encode($data));

                    $this->putToChart($server, $data['curtps'] ?? 0.0, $now);
                    $this->disk->put($this->getPathToChartJson($server), json_encode($this->charts[$server]['chart']));
                } catch (\Exception $exception) {
                    print "error: {$exception->getMessage()} \n";
                }
            }
            $tick++;
            sleep(1);
        }
        print "Скрипт остановлен\n";
    }
}
