<?php

namespace App\Providers\Filament;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;

class AdminAuthentication implements \Filament\Contracts\AuthenticationProvider
{
    public function getId(Model $user): string
    {
        return $user->id;
    }

    public function getUserName(Model $user): string
    {
        return $user->name;
    }

    public function getEmail(Model $user): string
    {
        return $user->email;
    }

    public function getAvatarUrl(Model $user): ?string
    {
        return null;
    }

    public function canAccessPanel(Model $user): bool
    {
        return $user->is_admin;
    }

    public function getAuthenticatable(): string
    {
        return \App\Models\User::class;
    }
} 