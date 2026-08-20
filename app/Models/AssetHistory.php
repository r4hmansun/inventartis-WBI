<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetHistory extends Model
{
    protected $fillable = [
        'asset_id',
        'action_type',
        'from_department_id',
        'to_department_id',
        'actor_user_id',
        'notes',
    ];

    /**
     * Prevent updates — audit trail is immutable (NFR-01).
     */
    public static function booted(): void
    {
        static::updating(function () {
            return false;
        });

        static::deleting(function () {
            return false;
        });
    }

    /**
     * The asset this history entry belongs to.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Source department (nullable for new registrations).
     */
    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    /**
     * Destination department.
     */
    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    /**
     * The user who performed this action.
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    /**
     * Human-readable action type label.
     */
    public function getActionLabelAttribute(): string
    {
        return match ($this->action_type) {
            'registration' => 'Registrasi Aset Baru',
            'initial_dispatch' => 'Penyaluran dari Gudang',
            'department_mutation' => 'Mutasi Antar-Departemen',
            'repair' => 'Perbaikan',
            'disposal' => 'Penghapusan/Disposal',
            default => $this->action_type,
        };
    }
}
