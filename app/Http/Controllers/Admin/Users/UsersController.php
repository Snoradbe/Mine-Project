<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Models\GuardKey;
use App\Models\User;
use App\Services\Groups\Group;
use App\Services\Groups\GroupsManager;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('admin.users.index', [
            'users' => User::orderBy('id', 'asc')->paginate(50)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $id
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return $this->view('admin.users.show', [
            'user' => $user,
            'groups' => GroupsManager::getGroups(),
            'userGroup' => $user->getPrimaryGroup()
        ]);
    }

    /**
     * Удалить почту.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeEmail(User $user)
    {
        $user->email = null;
        $user->email_verify = 0;

        $user->save();

        return $this->backSuccess('Почта удалена.');
    }

    /**
     * Удалить двухфакторную авторизацию.
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove2fa(User $user)
    {
        $user->g2fa_details = null;
        $user->save();

        GuardKey::deleteAllFromUser($user);

        return $this->backSuccess('Двухфакторная авторизация отключена.');
    }

    /**
     * Установить группу.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function setGroup(Request $request, User $user)
    {
        $groups = GroupsManager::getGroups();
        $listGroups = $groups->map(function (Group $group) {
            return $group->getName();
        })->toArray();

        $this->validate($request, [
            'group' => 'required|string|in:' . implode(',', $listGroups),
            'date' => 'nullable|date'
        ]);

        $group = GroupsManager::getGroupByName($request->get('group'));
        $date = empty($request->post('date')) ? null : Carbon::parse($request->post('date'));
        if (!is_null($date) && $date->getTimestamp() < time()) {
            return $this->backError('Дата должна быть больше, чем сегодня.');
        }

        GroupsManager::setGroup($user, $group, is_null($date) ? null : $date->toDateTime());

        return $this->backSuccess('Группа выдана.');
    }

    public function setCoins(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|integer'
        ]);

        $user->setCoins((int) $request->post('amount'));

        return $this->backSuccess('Баланс изменен');
    }
}
