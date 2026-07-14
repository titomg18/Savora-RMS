<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Helper methods untuk pengecekan role (sesuai dengan 5 role)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isWaiter(): bool
    {
        return $this->role === 'waiter';
    }

    public function isChef(): bool
    {
        return $this->role === 'chef';
    }

    // Redirect berdasarkan role
    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'admin'   => route('admin.dashboard'),
            'owner'   => route('owner.dashboard'),
            'cashier' => route('cashier.dashboard'),
            'waiter'  => route('waiter.dashboard'),
            'chef'    => route('chef.dashboard'),
            default   => route('dashboard'), // fallback jika ada role lain
        };
    }
}