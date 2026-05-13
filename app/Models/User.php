<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Роли пользователя (многие ко многим)
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')
            ->withTimestamps();
    }

    /**
     * Проверить, есть ли у пользователя роль
     */
    public function hasRole($roleSlug): bool
    {
        return $this->roles->contains('slug', $roleSlug);
    }

    /**
     * Проверить, есть ли у пользователя хотя бы одна из ролей
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles->whereIn('slug', $roleSlugs)->isNotEmpty();
    }

    /**
     * Назначить роль пользователю
     */
    public function assignRole($roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role && !$this->hasRole($roleSlug)) {
            $this->roles()->attach($role);
        }
    }

    /**
     * Удалить роль у пользователя
     */
    public function removeRole($roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        if ($role) {
            $this->roles()->detach($role);
        }
    }
}