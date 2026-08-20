<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Asset extends Model
{
    protected $fillable = [
        'asset_code',
        'name',
        'purchase_price',
        'purchase_date',
        'current_department_id',
        'status',
        'created_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'purchase_price' => 'decimal:2',
            'purchase_date' => 'date',
        ];
    }

    /**
     * The department currently holding this asset.
     */
    public function currentDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'current_department_id');
    }

    /**
     * The user who registered this asset.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Audit trail history for this asset.
     */
    public function histories(): HasMany
    {
        return $this->hasMany(AssetHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Mutation items referencing this asset.
     */
    public function mutationItems(): HasMany
    {
        return $this->hasMany(MutationItem::class);
    }

    /**
     * Scope: assets currently in storage (Gudang Inventaris).
     */
    public function scopeInStorage($query)
    {
        return $query->where('status', 'in_storage');
    }

    /**
     * Scope: assets currently active/in-use.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Check if purchase price meets capitalization threshold (BR-01).
     */
    public function meetsCapitalizationThreshold(): bool
    {
        return $this->purchase_price >= 500000;
    }

    /**
     * Generate asset code: AST/[KODE-DEPT]/[MM]/[YYYY]/[NO-URUT] (FR-REG-02).
     */
    public static function generateAssetCode(string $departmentCode): string
    {
        $month = now()->format('m');
        $year = now()->format('Y');
        $prefix = "AST/{$departmentCode}/{$month}/{$year}";

        $lastAsset = static::where('asset_code', 'like', "{$prefix}/%")
            ->orderBy('asset_code', 'desc')
            ->first();

        if ($lastAsset) {
            $lastNumber = (int) substr($lastAsset->asset_code, strrpos($lastAsset->asset_code, '/') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "{$prefix}/" . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }
}
