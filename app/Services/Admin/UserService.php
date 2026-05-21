<?php

namespace App\Services\Admin;

use App\Models\Region;
use App\Models\RoleRegionUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Создать user
     */
    public function create(Region $region, array $data): User
    {
        $role_id = $data['role_id'];
        unset($data['role_id']);
        $data['password'] = Hash::make("genby[eqkj");
        return DB::transaction(function () use ($region, $role_id, $data) {
            $user =  User::create($data);
            RoleRegionUser::create([
                'role_id' => $role_id,
                'region_id' => $region->id,
                'user_id' => $user->id
            ]);
            return $user;
        });


    }

    /**
     * Обновить данные user.
     */
    public function update(User $user, Region $region, array $data): User
    {
        $role_id = $data['role_id'];
        unset($data['role_id']);
        return DB::transaction(function () use ($user, $region, $role_id, $data) {
            RoleRegionUser::updateOrCreate(
                ['region_id' => $region->id, 'user_id' => $user->id], // По чем искать
                ['role_id' => $role_id]                               // Что обновлять/создавать
            );
            $user->update($data);
            return $user;
        });
    }

    /**
     * Удалить user.
     */
    public function delete(Region $region, User $user): bool
    {

        return DB::transaction(function () use ($user, $region) {
            RoleRegionUser::where([
                'region_id' => $region->id,
                'user_id' => $user->id
            ])->delete();
            return $user->delete();
        });
    }

}
