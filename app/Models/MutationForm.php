<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MutationForm extends Model
{
    protected $fillable = [
        'form_number',
        'from_department_id',
        'to_department_id',
        'reason',
        'status',
        'sender_user_id',
        'receiver_user_id',
        'executed_by_user_id',
        'sender_signature',
        'receiver_signature',
        'sender_signed_at',
        'receiver_signed_at',
        'archived_pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'sender_signed_at' => 'datetime',
            'receiver_signed_at' => 'datetime',
        ];
    }

    /**
     * Department sending the asset.
     */
    public function fromDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'from_department_id');
    }

    /**
     * Department receiving the asset.
     */
    public function toDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'to_department_id');
    }

    /**
     * The user who initiated the mutation (sender).
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    /**
     * The user who approved receiving (receiver).
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    /**
     * The inventory staff who executed the mutation.
     */
    public function executor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executed_by_user_id');
    }

    /**
     * Items included in this mutation form.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MutationItem::class);
    }

    /**
     * Check if form has dual-approval (BR-04).
     */
    public function hasDualApproval(): bool
    {
        return $this->sender_signature !== null && $this->receiver_signature !== null;
    }

    /**
     * Check if form is ready for execution by inventory staff.
     */
    public function isReadyForExecution(): bool
    {
        return $this->status === 'ready_for_execution' && $this->hasDualApproval();
    }

    /**
     * Generate form number: MUT/[MM]/[YYYY]/[NO-URUT].
     */
    public static function generateFormNumber(): string
    {
        $month = now()->format('m');
        $year = now()->format('Y');
        $prefix = "MUT/{$month}/{$year}";

        $lastForm = static::where('form_number', 'like', "{$prefix}/%")
            ->orderBy('form_number', 'desc')
            ->first();

        if ($lastForm) {
            $lastNumber = (int) substr($lastForm->form_number, strrpos($lastForm->form_number, '/') + 1);
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        return "{$prefix}/" . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }
}
