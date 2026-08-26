<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'department_id',
        'role',
    ];

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

    /**
     * The department this user belongs to.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Assets created/registered by this user.
     */
    public function createdAssets(): HasMany
    {
        return $this->hasMany(Asset::class, 'created_by_user_id');
    }

    /**
     * Mutation forms initiated by this user.
     */
    public function sentMutations(): HasMany
    {
        return $this->hasMany(MutationForm::class, 'sender_user_id');
    }

    /**
     * Mutation forms received by this user.
     */
    public function receivedMutations(): HasMany
    {
        return $this->hasMany(MutationForm::class, 'receiver_user_id');
    }

    /**
     * Check if user is Super Admin (has full master & role editing permissions).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Check if user has admin privileges (Super Admin or regular Admin).
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    /**
     * Check if user can edit/modify other users and their roles.
     */
    public function canEditUsers(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if user has finance role.
     */
    public function isFinance(): bool
    {
        return $this->role === 'finance';
    }

    /**
     * Check if user has inventory role.
     */
    public function isInventory(): bool
    {
        return $this->role === 'inventory';
    }

    /**
     * Check if user has the given role(s).
     */
    public function hasRole(string ...$roles): bool
    {
        if (in_array('admin', $roles) && ($this->role === 'admin' || $this->role === 'super_admin')) {
            return true;
        }

        return in_array($this->role, $roles);
    }

    /**
     * Human-readable role label.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'finance' => 'Bagian Keuangan',
            'inventory' => 'Bagian Inventaris',
            'user' => 'User / Departemen',
            default => $this->role,
        };
    }
}
