<?php

declare(strict_types=1);

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Region;
use App\Models\Role;
use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    // Внедряем сервис через конструктор
    public function __construct(
        protected UserService $userService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Region $region): View
    {
        // 1. Проверка прав (HTTP-слой)
        //        $this->authorize('viewAny', [User::class, $region]);

        $users = $region->users;

        // 2. HTTP-ответ
        return view('app.admin.users.index', compact('region', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region): View
    {
        // 1. Проверка прав (HTTP-слой)
        //        $this->authorize('create', [User::class, $region]);

        // 2. ИСПРАВЛЕНО: Динамически строим SQL-запрос в зависимости от того, КТО зашел в систему
        $rolesQuery = Role::select('id', 'name')->orderBy('id');

        // Если это НЕ Суперадмин (обычный региональный админ) — жестко скрываем роль 'admin'
        if (! Auth::user()->isSuperAdmin()) {
            $rolesQuery->whereNotIn('name', ['admin']);
        }

        // Выполняем SQL-запрос и получаем чистую, безопасную коллекцию моделей
        $roles = $rolesQuery->get();

        // 3. HTTP-ответ
        return view('app.admin.users.create', compact('region', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, Region $region): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        //        $this->authorize('create', [User::class, $region]);

        // 2. Делегирование бизнес-логики сервису
        $this->userService->create($region, $request->validated());

        // 3. HTTP-ответ
        return to_route('admin.users.index', ['region' => $region]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Region $region, User $user): View
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', [User::class, $region, $user]);

        // 2. Все роли, исключая роль "admin"
        $roles = Role::select('id', 'name')
            ->whereNotIn('name', ['admin'])
            ->orderBy('id')
            ->get();

        // 3. HTTP-ответ
        return view('app.admin.users.edit', compact('region', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, Region $region, User $user): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('update', [User::class, $region, $user]);

        // 2. Делегирование бизнес-логики сервису
        $this->userService->update($user, $region, $request->validated());

        // 3. HTTP-ответ
        return to_route('admin.users.index', ['region' => $region]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, User $user): RedirectResponse
    {
        // 1. Проверка прав (HTTP-слой)
        $this->authorize('delete', [User::class, $region, $user]);

        // 2. Делегирование бизнес-логики сервису
        $this->userService->delete($region, $user);

        // 3. HTTP-ответ
        return to_route('admin.users.index', ['region' => $region]);
    }
}
