<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function regions(): BelongsToMany
    {
        return $this->belongsToMany(Region::class, 'role_region_users', 'user_id', 'region_id');
    }

    public function roleInRegion(?Region $region = null)
    {
        if ($this->isSuperAdmin()) {
            $role = new Role;
            $role->forceFill([
                'id' => 1,
                'name' => 'Root',
            ]);

            return $role;
        } else {
            $data = RoleRegionUser::where('user_id', $this->id)->where('region_id', $region->id)->firstOrFail();

            return Role::findorfail($data->role_id);
        }
    }

    public function isCanViewRegion(Region $region): bool
    {
        $canViewRoles = config('roles.view_roles');

        return in_array($this->roleInRegion($region)->name, $canViewRoles);
    }

    public function isCanEditRegion(Region $region): bool
    {
        $canEditRoles = config('roles.edit_roles');

        return in_array($this->roleInRegion($region)->name, $canEditRoles) || $this->isSuperAdmin();
    }

    public function isAdminInRegion(Region $region): bool
    {
        $adminRoles = config('roles.admin_roles');

        return in_array($this->roleInRegion($region)->name, $adminRoles);
    }

    public function isMainEngineerInRegion(Region $region): bool
    {
        $MainEngineer = config('roles.main_engineer_roles');

        return in_array($this->roleInRegion($region)->name, $MainEngineer);
    }

    public function isSuperAdmin(): bool
    {
        return $this->id === 1;
    }
}
