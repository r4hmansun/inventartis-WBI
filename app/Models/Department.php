<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Users belonging to this department.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Assets currently held by this department.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'current_department_id');
    }

    /**
     * Mutation forms sent FROM this department.
     */
    public function outgoingMutations(): HasMany
    {
        return $this->hasMany(MutationForm::class, 'from_department_id');
    }

    /**
     * Mutation forms sent TO this department.
     */
    public function incomingMutations(): HasMany
    {
        return $this->hasMany(MutationForm::class, 'to_department_id');
    }

    /**
     * Check if this is the Gudang Inventaris department.
     */
    public function isGudangInventaris(): bool
    {
        return $this->code === 'GDG-INV';
    }

    /**
     * Scope to only active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
