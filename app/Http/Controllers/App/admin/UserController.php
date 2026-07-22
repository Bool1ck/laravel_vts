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
    // Додаємо сервіс через конструктор
    public function __construct(
        protected UserService $userService,
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Region $region): View
    {
        // 1. користувачі регіона
        $users = $region->users;

        // 2. HTTP-відповідь
        return view('app.admin.users.index', compact('region', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region): View
    {
        // 1. Динамічний запит ролей
        $rolesQuery = Role::select('id', 'name')->orderBy('id');

        // 2. Для не супер адміна скриваємо роль адміна
        if (! Auth::user()->isSuperAdmin()) {
            $rolesQuery->whereNotIn('name', ['admin']);
        }

        // 3. Отримуємо ролі
        $roles = $rolesQuery->get();

        // 4. HTTP-відповідь
        return view('app.admin.users.create', compact('region', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, Region $region): RedirectResponse
    {
        // 1. Обробка даних сервісом
        $this->userService->create($region, $request->validated());

        // 2. HTTP-редірект
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
        // 1. Перевірка прав прав (HTTP-шар)
        $this->authorize('update', [User::class, $region, $user]);

        // 2. Всі ролі, виключаючи роль "admin"
        $roles = Role::select('id', 'name')
            ->whereNotIn('name', ['admin'])
            ->orderBy('id')
            ->get();

        // 3. HTTP-відповідь
        return view('app.admin.users.edit', compact('region', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, Region $region, User $user): RedirectResponse
    {
        // 1. Перевірка прав прав (HTTP-шар)
        $this->authorize('update', [User::class, $region, $user]);

        // 2. Обробка даних сервісом
        $this->userService->update($user, $region, $request->validated());

        // 3. HTTP-редірект
        return to_route('admin.users.index', ['region' => $region]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, User $user): RedirectResponse
    {
        // 1. Перевірка прав прав (HTTP-шар)
        $this->authorize('delete', [User::class, $region, $user]);

        // 2. Обробка даних сервісом
        $this->userService->delete($region, $user);

        // 3. HTTP-редірект
        return to_route('admin.users.index', ['region' => $region]);
    }
}
