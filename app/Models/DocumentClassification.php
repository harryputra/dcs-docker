<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentClassification extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'parent_id',
        'level',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $appends = ['full_code'];

    /**
     * Get parent classification
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(DocumentClassification::class, 'parent_id');
    }

    /**
     * Get children classifications
     */
    public function children(): HasMany
    {
        return $this->hasMany(DocumentClassification::class, 'parent_id')->orderBy('order');
    }

    /**
     * Get full code path (e.g., HM.01.01)
     */
    public function getFullCodeAttribute(): string
    {
        $codes = [];
        $current = $this;

        while ($current) {
            array_unshift($codes, $current->code);
            $current = $current->parent;
        }

        return implode('.', $codes);
    }

    /**
     * Scope untuk level tertentu
     */
    public function scopeLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    /**
     * Scope untuk aktif saja
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
