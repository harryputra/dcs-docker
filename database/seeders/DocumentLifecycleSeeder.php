<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentLifecycleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminId = 1;
        $catSopId = 1;
        $classId = 4;

        // Ensure directories exist
        \Illuminate\Support\Facades\Storage::disk('dokumen-approved')->makeDirectory('');

        // 1. Study Case: Replacement
        // NEW VERSION
        $newDoc = \App\Models\Document::create([
            'title' => 'SOP Alur Pendaftaran Pasien V.2',
            'code' => 'KS.01.01.13/002-PKM GRD/SOP/IV/2026',
            'category_id' => $catSopId,
            'uploaded_by' => $adminId,
            'is_active' => true,
            'status_document' => 'Aktif',
            'published_date' => '2026-04-16',
            'classification_id' => $classId,
            'sequence_number' => 2,
        ]);

        // OLD VERSION
        $oldDoc = \App\Models\Document::create([
            'title' => 'SOP Alur Pendaftaran Pasien V.1',
            'code' => 'KS.01.01.13/001-PKM GRD/SOP/I/2024',
            'category_id' => $catSopId,
            'uploaded_by' => $adminId,
            'is_active' => false,
            'status_document' => 'Diganti',
            'replaced_by_id' => $newDoc->id,
            'published_date' => '2024-01-01',
            'classification_id' => $classId,
            'sequence_number' => 1,
        ]);

        // 2. Study Case: Revocation (Dicabut)
        $revokedDoc = \App\Models\Document::create([
            'title' => 'SK Penetapan Area Terbatas Tahap 1',
            'code' => 'KS.01.01.13/003-PKM GRD/SK/V/2025',
            'category_id' => 2, // SK
            'uploaded_by' => $adminId,
            'is_active' => false,
            'status_document' => 'Dicabut',
            'published_date' => '2025-05-10',
            'classification_id' => $classId,
            'sequence_number' => 3,
        ]);

        // 3. Study Case: Expiration (Kadaluarsa)
        $expiredDoc = \App\Models\Document::create([
            'title' => 'Surat Edaran Protokol Protokol Kesehatan 2024',
            'code' => 'KS.01.01.13/004-PKM GRD/SK/XII/2024',
            'category_id' => 2,
            'uploaded_by' => $adminId,
            'is_active' => false,
            'status_document' => 'Kadaluarsa',
            'expired_at' => '2025-12-31',
            'published_date' => '2024-12-01',
            'classification_id' => $classId,
            'sequence_number' => 4,
        ]);

        // Create Revisions and Dummy PDF Files
        foreach ([$newDoc, $oldDoc, $revokedDoc, $expiredDoc] as $doc) {
            $fileName = str_replace(['/', '\\'], '-', $doc->code) . '_' . preg_replace('/[\/\\\?\%\*\:\|\\"\<\>\.\(\)]/', '_', $doc->title) . '_(Signed).pdf';
            
            // Generate valid-ish PDF header to satisfy mime checks
            \Illuminate\Support\Facades\Storage::disk('dokumen-approved')->put($fileName, "%PDF-1.4\n1 0 obj\n<<\n/Title (Dummy PDF for " . $doc->title . ")\n>>\nendobj\ntrailer\n<<\n/Root 1 0 R\n>>\n%%EOF");

            $revision = \App\Models\DocumentRevision::create([
                'document_id' => $doc->id,
                'file_path' => $fileName,
                'revised_by' => $adminId,
                'revision_number' => 1,
                'status' => 'Disetujui',
                'description' => 'Initial upload for study case',
            ]);

            $doc->update(['current_revision_id' => $revision->id]);
            
            \App\Models\DocumentHistory::create([
                'document_id' => $doc->id,
                'revision_id' => $revision->id,
                'action' => 'Approved',
                'performed_by' => $adminId,
                'reason' => 'Auto-approved for study case setup',
            ]);
        }
    }
}
