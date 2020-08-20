<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Services\ConfigSaver;
use Illuminate\Http\Request;

class ServersSortingController extends Controller
{
    public function index()
    {
        $sorted = config('servers-sorting', []);
        uasort($sorted, function ($serv1, $serv2) {
           return $serv1 < $serv2;
        });
        return $this->view('admin.servers-sorting.index', [
            'sorted' => $sorted
        ]);
    }

    public function save(Request $request, ConfigSaver $configSaver)
    {
        $request->validate([
            'servers' => 'required|array',
        ]);

        $result = [];
        foreach ($request->post('servers') as $server => $weight)
        {
            if (is_string($server) && $weight > 0) {
                $result[$server] = (int) $weight;
            }
        }

        $configSaver->save('servers-sorting', $result);
        $configSaver->updateCache();

        return $this->backSuccess('Сортировка сохранена');
    }

    public function add(Request $request, ConfigSaver $configSaver)
    {
        $request->validate([
            'server' => 'required|string',
            'weight' => 'required|integer|min:1'
        ]);

        $configSaver->save('servers-sorting', array_merge(config('servers-sorting', []), [
            $request->post('server') => (int) $request->post('weight')
        ]));
        $configSaver->updateCache();

        return $this->backSuccess('Сервер добавлен');
    }
}
