<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ConfigSaver;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Вывести список админов, имеющих доступ к админ-панели.
     *
     * @return \Illuminate\View\View
     */
    public function admins()
    {
        return view('admin.settings.admins', [
            'admins' => config('admins', [])
        ]);
    }

    /**
     * Изменить список админов.
     *
     * @param Request $request
     * @param ConfigSaver $configSaver
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function adminsAdd(Request $request, ConfigSaver $configSaver)
    {
        $this->validate($request, [
            'nickname' => 'required|string'
        ]);

        $name = trim($request->post('nickname'));
        $user = User::findByName($name)->first();
        if (is_null($user)) {
            return $this->backError('Игрок не найден.');
        }

        $admins = config('admins', []);
        if (in_array($user->playername, $admins)) {
            return $this->backError('Такой админ уже есть.');
        }

        $admins[] = $user->playername;

        $configSaver->save('admins', $admins);
        $configSaver->updateCache();

        return $this->backSuccess('Список админов изменен.');
    }

    /**
     * Изменить список админов.
     *
     * @param Request $request
     * @param ConfigSaver $configSaver
     * @param string $admin
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function adminsRemove(Request $request, ConfigSaver $configSaver, string $admin)
    {
        $username = $this->user()->playername;
        if ($username == $admin) {
            return $this->backError('Нельзя удалить себя.');
        }

        $admins = config('admins', []);
        $search = array_search($admin, $admins);
        if ($search === false) {
            return $this->backError('Админ не найден.');
        }

        unset($admins[$search]);

        $configSaver->save('admins', $admins);
        $configSaver->updateCache();

        return $this->backSuccess('Список админов изменен.');
    }

    public function base()
    {
        return view('admin.settings.base', [
            //
        ]);
    }
}
