<?php

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
        return $this->belongsToMany(Region::class,'role_region_users','user_id','region_id');
    }

    public function regionRoles() {
        return $this->hasMany(RoleRegionUser::class);
    }

    public function isAdminInRegion(Region $region) : bool {
        $data = RoleRegionUser::where('user_id', $this->id)->where('region_id', $region->id)->first();
        $role = Role::where('id',$data->role_id)->first();
        return $role->name == 'admin';
    }

}
