<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $table = 'documents';
    protected $fillable = [
        'title',
        'code',
        'category_id',
        'uploaded_by',
        'is_active',
        'current_revision_id',
        'published_date',
        'classification_id',
        'sequence_number',
        'puskesmas_code',
    ];

    public function revisions()
    {
        return $this->hasMany(DocumentRevision::class);
    }

    public function latestRevision()
    {
        return $this->hasOne(DocumentRevision::class)->latestOfMany();
    }

    public function currentRevision()
    {
        return $this->belongsTo(DocumentRevision::class, 'current_revision_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function histories(): HasMany
    {
        return $this->hasMany(DocumentHistory::class);
    }

    public function latestHistory()
    {
        return $this->hasOne(DocumentHistory::class)->latestOfMany();
    }

    public function classification(): BelongsTo
    {
        return $this->belongsTo(Classification::class, 'classification_id');
    }

    /**
     * Generate document code based on classification and sequence
     * Format: [KodeKlasifikasi]/[Sequence]-[Puskesmas]/[Category]/[Month]/[Year]
     * Example: KS.01.01.13/020-PKM GRD/SK/I/2025
     *
     * Phase 1 (Approval): KS.01.01.13/020-PKM GRD/SK/-/-
     * Phase 2 (Published): KS.01.01.13/020-PKM GRD/SK/XI/2025
     */
    public function generateDocumentCode(): string
    {
        // Get classification code
        $classCode = $this->classification->kode_klasifikasi ?? '';

        // Format sequence number with leading zeros (3 digits)
        $sequence = str_pad($this->sequence_number, 3, '0', STR_PAD_LEFT);

        // Get puskesmas code (default to PKM GRD)
        $puskesmas = $this->puskesmas_code ?? 'PKM GRD';

        // Get category code
        $categoryCode = $this->category->code ?? '';

        // Convert month to Roman numerals (only if published_date exists)
        $month = $this->published_date ? $this->getRomanMonth($this->published_date) : '-';

        // Get year (only if published_date exists)
        $year = $this->published_date ? date('Y', strtotime($this->published_date)) : '-';

        // Build complete code: KS.01.01.13/020-PKM GRD/SK/XI/2025
        // Or partial: KS.01.01.13/020-PKM GRD/SK/-/-
        return "{$classCode}/{$sequence}-{$puskesmas}/{$categoryCode}/{$month}/{$year}";
    }
    /**
     * Convert month number to Roman numerals
     */
    private function getRomanMonth(string $date): string
    {
        $month = (int) date('n', strtotime($date));
        $romans = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        return $romans[$month] ?? '';
    }

    /**
     * Get the next sequence number for the given classification
     */
    public static function getNextSequenceNumber($classificationId): int
    {
        $maxSequence = self::where('classification_id', $classificationId)
            ->max('sequence_number');

        return $maxSequence ? $maxSequence + 1 : 1;
    }
}
