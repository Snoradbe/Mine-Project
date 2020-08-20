<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ConfigSaver;

class IndexController extends Controller
{
    /**
     * Вывести главную страницу админки.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index', [
            'enabled' => config('site.enabled', true),
            'countUsers' => User::count(),
            'diskSpace' => $this->getDiskSpace()
        ]);
    }

    /**
     * Очистить кэш конфига.
     *
     * @param ConfigSaver $configSaver
     * @return \Illuminate\Http\RedirectResponse
     */
    public function clearConfigCache(ConfigSaver $configSaver)
    {
        $configSaver->reCache();

        return $this->backSuccess('Кэш конфига очищен.');
    }

    /**
     * Данные свободного места на диске.
     * А также формат.
     *
     * @return string
     */
    private function getDiskSpace(): string
    {
        if (function_exists('disk_free_space')) {
            $bytes = disk_free_space(".");
            $siPrefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
            $base = 1024;
            $class = min((int)log($bytes , $base) , count($siPrefix) - 1);

            return sprintf('%1.2f' , $bytes / pow($base, $class)) . ' ' . $siPrefix[$class];
        }

        return '???';
    }
}
