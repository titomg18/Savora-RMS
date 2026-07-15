<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Daftar role yang tersedia. Dipakai di form <select> dan validasi.
     */
    public const ROLES = ['admin', 'owner', 'cashier', 'waiter', 'chef'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    /**
     * Role yang boleh mengelola CRUD user lain lewat halaman admin.
     * Sesuaikan kalau kamu mau role lain juga bisa akses.
     */
    public function canManageUsers(): bool
    {
        return in_array($this->role, ['admin', 'owner'], true);
    }

    /**
     * Dipakai LoginController & route /dashboard untuk redirect sesuai role.
     */
    public function getDashboardRoute(): string
    {
        return match ($this->role) {
            'admin'   => route('admin.dashboard'),
            'owner'   => route('owner.dashboard'),
            'cashier' => route('cashier.dashboard'),
            'waiter'  => route('waiter.dashboard'),
            'chef'    => route('chef.dashboard'),
            default   => route('login'),
        };
    }
}