<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MutationItem extends Model
{
    protected $fillable = [
        'mutation_form_id',
        'asset_id',
        'item_condition',
    ];

    /**
     * The mutation form this item belongs to.
     */
    public function mutationForm(): BelongsTo
    {
        return $this->belongsTo(MutationForm::class);
    }

    /**
     * The asset being mutated.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }
}
