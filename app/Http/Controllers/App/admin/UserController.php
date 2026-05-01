<?php

namespace App\Http\Controllers\App\admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\Region;
use App\Models\Role;
use App\Models\RoleRegionUser;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Region $region)
    {
        return view('app.admin.users.index', compact('region'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Region $region)
    {
        $roles = Role::all()->filter(function ($role) {return !in_array($role->name,['admin']);});
        return view('app.admin.users.create', compact('region', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, Region $region)
    {
        $validated = $request->validated();
        if ($region->isHasUserByEmail($validated['email'])) {
            return back()->withErrors(['custom_field' => 'Користувач з таким email вже існує!'])->withInput();
        }
        $role_id = $validated['role_id'];
        unset($validated['role_id']);
        $validated['password'] = Hash::make("genby[eqkj");

        $user = User::create($validated);
        $roleRegionUser = RoleRegionUser::create(['role_id' => $role_id, 'region_id' => $region->id, 'user_id' => $user->id]);
        return redirect(route('admin.users.index', ['region' => $region]));
        //
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
    public function edit(Region $region, User $user)
    {
        $roles = Role::all()->filter(function ($role) {return !in_array($role->name,['admin']);});
        return view('app.admin.users.edit', compact('region', 'user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, Region $region, User $user)
    {
        $validated = $request->validated();
        $role_id = $validated['role_id'];
        unset($validated['role_id']);
        $user->update($validated);
        $roleRegionUser = RoleRegionUser::where(['region_id' => $region->id, 'user_id' =>
            $user->id])->update(['role_id' => $role_id]);
        return redirect(route('admin.users.index', ['region' => $region]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region, User $user)
    {

        $roleRegionUser = RoleRegionUser::where(['region_id' => $region->id, 'user_id' =>
            $user->id])->delete();
        $user->delete();
        return redirect(route('admin.users.index', ['region' => $region]));
    }
}
