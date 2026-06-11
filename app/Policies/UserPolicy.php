<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $actor): bool
    {
        return $actor->can('manage users');
    }

    public function view(User $actor, User $target): bool
    {
        return $actor->can('manage users');
    }

    public function create(User $actor): bool
    {
        return $actor->can('manage users');
    }

    public function update(User $actor, User $target): bool
    {
        if (! $actor->can('manage users')) {
            return false;
        }

        if ($target->hasRole(UserRole::Developer->value) && ! $actor->hasRole(UserRole::Developer->value)) {
            return false;
        }

        return $actor->id !== $target->id || $actor->hasRole(UserRole::Developer->value);
    }

    public function delete(User $actor, User $target): bool
    {
        if ($actor->id === $target->id) {
            return false;
        }

        if ($target->hasRole(UserRole::Developer->value)) {
            return $actor->hasRole(UserRole::Developer->value);
        }

        return $actor->can('manage users');
    }

    public function assignRole(User $actor, User $target, string $role): bool
    {
        if ($role === UserRole::Developer->value) {
            return $actor->hasRole(UserRole::Developer->value);
        }

        if ($target->hasRole(UserRole::Developer->value) && ! $actor->hasRole(UserRole::Developer->value)) {
            return false;
        }

        return $actor->can('manage roles') || $actor->can('manage users');
    }
}
