<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'reference', 'type', 'name', 'phone', 'email', 'project_id', 'project_name',
    'partner_role', 'area', 'budget', 'message', 'status',
])]
class Inquiry extends Model
{
    public function isPartnerLead(): bool
    {
        return $this->type === 'partner';
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(InquiryNote::class)->latest();
    }
}
