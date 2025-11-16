<?php

namespace App\Http\Controllers;

use App\Events\NewApprovalDocument;
use App\Events\NewCreatedDocument;
use App\Models\Category;
use App\Models\Document;
use App\Models\DocumentRevision;
use App\Models\DocumentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentRevisionController extends Controller
{

    public function index()
    {
        $documents = Document::whereHas('uploader.roles', function ($query) {
            $query->whereIn('id', auth()->user()->roles->pluck('id'));
        })->with(['uploader', 'latestHistory'])->get();
        return view('admin.my_document.index', compact('documents'));
    }

    public function indexApproval()
    {
        $documents = Document::with('currentRevision', 'uploader', 'latestHistory')
            ->get();

        $roles = Auth::user()->roles->pluck('slug');

        $categories = Category::all();
        return view('admin.document_approve.index', compact('documents', 'categories', 'roles'));
    }

    public function getDoc(Request $req)
    {
        $documentRevision = DocumentRevision::with('document')->with('reviser')->findOrFail($req->id);
        if (!$documentRevision) {
            return response()->json(['message' => 'Document not found'], 404);
        }
        $history = DocumentHistory::with('revision')
            ->where('document_id', $documentRevision->document->id)
            ->where('revision_id', $documentRevision->id)
            ->where('performed_by', $documentRevision->reviser->id)
            ->where('action', 'Revised')
            ->first();

        $reason = $history['reason'] ?? '';

        $userRoles = Auth::user()->roles->pluck('slug');
        $roles = $userRoles->toArray();

        $data = [
            'id' => $documentRevision->id,
            'judul' => $documentRevision->document->title,
            'code' => $documentRevision->document->code,
            'classification_id' => $documentRevision->document->classification_id,
            'category' => $documentRevision->document->category->name,
            'uploader' => $documentRevision->document->uploader->name,
            'status' => $documentRevision->status,
            'url' => route('document_revision.show-file', ['filename' => $documentRevision->file_path]),
            'view_url' => route('document_revision.view-file', ['filename' => $documentRevision->file_path]),
            'acc_format' => $documentRevision->acc_format,
            'acc_content' => $documentRevision->acc_content,
            'reason' => $reason ?? '',
            'roles' => $roles
        ];

        return response()->json(['data' => $data], 200);
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.my_document.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'category_id' => 'required',
                'rev' => 'required|array',
                'file_path' => 'required|file|mimes:doc,docx,xls,xlsx|max:20480',
                'description' => 'required|string',
                'reason' => 'required|string|max:255',
                'created_at' => 'required|date|before_or_equal:today',
            ]);

            $file = $request->file('file_path');
            $fileExtension = $file->getClientOriginalExtension();

            // Dokumen revisi: gunakan TEMP untuk sementara
            $tempCode = 'TEMP_' . time();
            $fileName = str_replace(['/', '\\'], '-', $tempCode) . '_' . preg_replace('/[\/\\\?\%\*\:\|\\"\<\>\.\(\)]/', '_', $validated['title']) . '.' . $fileExtension;
            // Simpan ke dokumen-revision untuk proses approval
            Storage::disk('dokumen-revision')->put($fileName, file_get_contents($file));

            $document = Document::create([
                'title' => $validated['title'],
                'code' => null,
                'category_id' => $validated['category_id'],
                'uploaded_by' => Auth::id(),
                'current_revision_id' => null,
                'created_at' => $validated['created_at'],
                'published_date' => null,
                'is_active' => 0,
            ]);

            $revisionData = [
                'document_id' => $document->id,
                'file_path' => $fileName,
                'revised_by' => Auth::id(),
                'revision_number' => 1,
                'description' => $validated['description'],
                'revised_doc' => $validated['rev']
            ];

            $revision = DocumentRevision::create($revisionData);

            // Untuk dokumen lama, JANGAN ubah created_at revision (biar tetap tanggal upload)
            // History approval yang pakai published_date

            foreach ($validated['rev'] ?? [] as $rev) {
                $doc = Document::findOrFail($rev);
                $doc->currentRevision->update([
                    'status' => 'Proses Revisi'
                ]);
            }

            $document->update(['current_revision_id' => $revision->id]);

            DocumentHistory::create([
                'document_id' => $document->id,
                'revision_id' => $revision->id,
                'action' => 'Created',
                'performed_by' => Auth::id(),
                'reason' => $validated['reason'],
            ]);

            // Kirim notifikasi ke Pengendali Dokumen
            event(new NewCreatedDocument($document, 'Dokumen "' . $document->title . '" telah dibuat oleh ' . $document->uploader->name));

            return redirect()->route('document_revision.index')->with('success', 'Dokumen revisi berhasil dibuat dan menunggu persetujuan.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Error validasi
            $errors = $e->errors();
            if (isset($errors['file_path'])) {
                return redirect()->back()->with('error', 'Format file tidak sesuai. Dokumen revisi harus dalam format DOC, DOCX, XLS, atau XLSX. Maksimal 20MB.')->withInput();
            }
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Error umum dengan pesan user-friendly
            $errorMessage = 'Gagal membuat dokumen revisi. ';

            if (str_contains($e->getMessage(), 'Duplicate')) {
                $errorMessage .= 'Dokumen sudah ada dalam sistem.';
            } elseif (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'storage')) {
                $errorMessage .= 'Gagal menyimpan file. Periksa ukuran dan format file.';
            } else {
                $errorMessage .= 'Terjadi kesalahan pada sistem. Silakan coba lagi atau hubungi administrator.';
            }

            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    public function show(DocumentRevision $documentRevision)
    {
        if ($documentRevision->checkUploaderRoles()) {
            return view('admin.my_document.show', compact('documentRevision'));
        }

        abort(404);
    }

    public function edit(DocumentRevision $documentRevision)
    {
        $rightRole = $documentRevision->checkUploaderRoles();

        if ($documentRevision->status === 'Expired') {
            return redirect()->route('documents.show', ['document' => $documentRevision->document_id])
                ->with('info', 'Dokumen ini sudah tidak berlaku. Anda dialihkan ke halaman detail dokumen.');
        }

        // Jika dokumen sudah ada approval dari Pendok atau Mutu, tidak bisa edit (race condition protection)
        if (($documentRevision->acc_format || $documentRevision->acc_content) && $rightRole) {
            return redirect()->route('document_revision.show', ['documentRevision' => $documentRevision->id])
                ->with('info', 'Dokumen sedang dalam proses approval. Anda tidak dapat mengedit saat ini.');
        }

        // Method ini hanya untuk revisi dokumen yang sudah disetujui atau pengajuan revisi
        if (($documentRevision->status === 'Disetujui' || $documentRevision->status === 'Pengajuan Revisi') && $rightRole) {
            $reason = $documentRevision->status === 'Pengajuan Revisi' ? DocumentHistory::with('revision')->where('document_id', $documentRevision->document->id)->where('revision_id', $documentRevision->id)->where('action', 'Rejected')->first()->reason : '';
            $approvedDocs = Document::where('is_active', true)
                ->whereHas('currentRevision', function ($query) {
                    $query->where('status', 'Disetujui');
                })
                ->where('id', '!=', $documentRevision->document_id)
                ->where('category_id', $documentRevision->document->category_id)
                ->with('currentRevision')
                ->get();
            $categories = Category::all();
            return view('admin.my_document.edit', compact('documentRevision', 'categories', 'approvedDocs', 'reason'));
        } else {
            return abort(404);
        }
    }

    public function editApproval(DocumentRevision $documentRevision)
    {
        if ($documentRevision->status === 'Draft' && $documentRevision->acc_format && $documentRevision->acc_content && auth()->user()->isRole('pengendali-dokumen')) {
            $document = $documentRevision->document;
            return view('admin.document_approve.edit', compact('document', 'documentRevision'));
        }

        return abort(404);
    }

    public function update(Request $request, DocumentRevision $documentRevision)
    {
        try {
            // Cek apakah status dokumen sudah disetujui atau sedang dalam proses approval
            if (in_array($documentRevision->status, ['Disetujui'])) {
                return redirect()->back()->with('error', 'Dokumen yang sudah disetujui tidak dapat diubah. Silakan buat revisi baru jika diperlukan.');
            }

            // Cek apakah dokumen sedang dalam proses approval (acc_format atau acc_content sudah true)
            if ($documentRevision->acc_format || $documentRevision->acc_content) {
                return redirect()->back()->with('error', 'Dokumen sedang dalam proses approval. Tidak dapat diubah saat ini. Silakan hubungi approver untuk membatalkan approval terlebih dahulu.');
            }

            $rules = [
                'title' => 'required|string|max:255',
                'category_id' => 'required',
                'rev' => 'nullable|array',
                'file_path' => 'required|file|mimes:pdf,doc,docx,ppt,pptx',
                'description' => 'required|string',
            ];

            if ($documentRevision->status !== 'Pengajuan Revisi') {
                $rules['reason'] = 'required|string|max:255';
            }

            $validated = $request->validate($rules);

            $file = $request->file('file_path');
            $fileExtension = $file->getClientOriginalExtension();
            // Gunakan code yang sudah ada dari document (tidak berubah saat revisi)
            $docCode = $documentRevision->document->code;
            $fileName = str_replace(['/', '\\'], '-', $docCode) . '_' . preg_replace('/[\/\\\?\%\*\:\|\\"\<\>\.\(\)]/', '_', $validated['title']) . '.' . $fileExtension;
            if (Storage::disk('dokumen-revision')->exists($documentRevision->file_path)) {
                Storage::disk('dokumen-revision')->delete($documentRevision->file_path);
            }
            Storage::disk('dokumen-revision')->put($fileName, file_get_contents($file));
            $documentRevision->update([
                'status' => 'Proses Revisi'
            ]);

            $currentRevDoc = $documentRevision->revised_doc;
            if ($currentRevDoc) {
                // Get the new values to add
                $newValues = array_diff($validated['rev'] ?? [], $currentRevDoc);

                if (!empty($newValues)) {
                    $currentRevDoc = array_merge($currentRevDoc, $newValues);
                }
            }

            if (empty($currentRevDoc)) {
                $currentRevDoc = null;
            }

            DocumentRevision::create([
                'document_id' => $documentRevision->document_id,
                'file_path' => $fileName,
                'revised_by' => Auth::id(),
                'revision_number' => $documentRevision->revision_number + 1,
                'description' => $validated['description'],
                'revised_doc' => $currentRevDoc ?? $validated['rev'] ?? null
            ]);

            $documentRevision->document->update([
                'title' => $validated['title'],
                'category_id' => $validated['category_id'],
                // code tidak diubah karena harus tetap konsisten
            ]);

            foreach ($validated['rev'] ?? [] as $rev) {
                $doc = Document::findOrFail($rev);
                $doc->currentRevision->update([
                    'status' => 'Proses Revisi'
                ]);
            }

            // Log to DocumentHistory
            DocumentHistory::create([
                'document_id' => $documentRevision->document_id,
                'revision_id' => $documentRevision->id,
                'action' => 'Revised',
                'performed_by' => Auth::id(),
                'reason' => $validated['reason'] ?? null,
            ]);

            event(new NewCreatedDocument($documentRevision->document, 'Dokumen ' . $documentRevision->document->title . ' telah direvisi oleh ' . $documentRevision->document->uploader->name . '.'));

            // Redirect berdasarkan role
            if (auth()->user()->isRole('Kepala-Puskesmas')) {
                return redirect()->route('active_document.index')
                    ->with('success', 'Pengajuan revisi dokumen berhasil dikirim. Menunggu proses persetujuan.');
            }

            return redirect()->route('document_revision.index')->with('success', 'Dokumen berhasil diperbarui dan menunggu persetujuan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui dokumen. Silakan coba lagi. Error: ' . $e->getMessage())->withInput();
        }
    }

    public function updateApproval(Request $request, DocumentRevision $documentRevision)
    {
        try {
            $rules = [
                'status' => 'required|in:Disetujui,Pengajuan Revisi,Draft',
                'reason' => 'required_if:status,Pengajuan Revisi|string|max:255',
                'file' => 'required_if:status,Disetujui|file|mimes:pdf|max:20480',
                'classification_id' => 'nullable|exists:classifications,id',
            ];

            if (auth()->user()->isRole('Administrator')) {
                $rules['acc_format'] = 'boolean';
                $rules['acc_content'] = 'boolean';
            }

            $validator = Validator::make($request->all(), $rules);

            $validator->after(function ($validator) use ($request) {
                if (auth()->user()->isRole('Administrator')) {
                    if ($request->input('acc_format') == false && $request->input('acc_content') == false && $request->input('reason') === '') {
                        $validator->errors()->add('acc_format', 'Either acc_format or acc_content must be true.');
                        $validator->errors()->add('acc_content', 'Either acc_format or acc_content must be true.');
                    }
                }
            });

            $validated = $validator->validate();

            // TAHAP 1: Pengendali Dokumen pilih klasifikasi dan generate kode parsial
            if (isset($validated['classification_id']) && auth()->user()->isRole('Pengendali-Dokumen')) {
                $document = $documentRevision->document;

                // Cek apakah ini dokumen baru atau revisi
                if (!$document->sequence_number) {
                    // Dokumen BARU: generate sequence number baru
                    $document->classification_id = $validated['classification_id'];
                    $document->sequence_number = Document::getNextSequenceNumber($validated['classification_id']);
                    $document->puskesmas_code = 'PKM GRD';

                    // published_date masih NULL untuk kode parsial dokumen baru
                    $document->published_date = null;
                } else {
                    // Dokumen REVISI: tetap gunakan sequence_number dan published_date yang lama
                    if ($document->classification_id != $validated['classification_id']) {
                        $document->classification_id = $validated['classification_id'];
                    }
                    // sequence_number dan published_date TIDAK diubah untuk revisi
                    // published_date tetap ada sehingga bulan/tahun tidak hilang
                }

                $document->save();

                // Load relasi untuk generate code
                $document->load(['classification', 'category']);

                // Generate kode:
                // - Dokumen baru: HM.01.01.13/001-PKM GRD/SK/-/- (bulan/tahun masih dash)
                // - Dokumen revisi: HM.01.01.13/001-PKM GRD/SK/XI/2025 (bulan/tahun tetap ada)
                $generatedCode = $document->generateDocumentCode();
                $document->code = $generatedCode;
                $document->save();

                // Rename file dengan code parsial
                $oldFilePath = $documentRevision->file_path;
                $fileExtension = pathinfo($oldFilePath, PATHINFO_EXTENSION);
                $newFileName = str_replace(['/', '\\'], '-', $generatedCode) . '_' . preg_replace('/[\/\\\?\%\*\:\|\\"\<\>\.\(\)]/', '_', $document->title) . '.' . $fileExtension;

                if (Storage::disk('dokumen-revision')->exists($oldFilePath)) {
                    $oldContent = Storage::disk('dokumen-revision')->get($oldFilePath);
                    Storage::disk('dokumen-revision')->put($newFileName, $oldContent);
                    Storage::disk('dokumen-revision')->delete($oldFilePath);
                    $documentRevision->update(['file_path' => $newFileName]);
                }
            }

            $revData = [
                'status' => $validated['status'],
                'acc_format' => $validated['status'] == 'Pengajuan Revisi' ? false : (auth()->user()->isRole('Pengendali-Dokumen') ? true : $validated['acc_format'] ?? $documentRevision->acc_format),
                'acc_content' => $validated['status'] == 'Pengajuan Revisi' ? false : (auth()->user()->isRole('Bagian-Mutu') ? true : $validated['acc_content'] ?? $documentRevision->acc_content),
            ];

            $documentRevision->update($revData);

            if ($validated['status'] == 'Pengajuan Revisi') {
                $documentRevision->document->update(['is_active' => false]);
            }

            $act = match ($validated['status']) {
                'Disetujui' => 'Approved',
                'Draft' => 'Approved',
                default => 'Rejected',
            };

            // Check jika Pengendali Dokumen upload file signed setelah acc_format dan acc_content true
            $uploadFileSigned = $validated['status'] === 'Disetujui'
                && auth()->user()->isRole('Pengendali-Dokumen')
                && $documentRevision->acc_format
                && $documentRevision->acc_content;

            $revisorRoles = $documentRevision->reviser->roles->pluck('id')->toArray();
            $roles = [1];
            $roles = array_merge($roles, $revisorRoles);

            // Remove duplicates, if needed
            $roles = array_unique($roles);

            if ($uploadFileSigned) {

                $file = $request->file('file');
                $fileExtension = $file->getClientOriginalExtension();

                // TAHAP 2: Upload file signed, update published_date dan regenerate kode lengkap
                $document = $documentRevision->document;

                // Set published_date untuk kode lengkap
                $document->published_date = now()->format('Y-m-d');
                $document->save();

                // Load relasi untuk generate code lengkap
                $document->load(['classification', 'category']);

                // Regenerate kode dokumen lengkap: HM.01.01.13/001-PKM GRD/SK/XI/2025
                $generatedCode = $document->generateDocumentCode();
                $document->code = $generatedCode;
                $document->save();

                $fileName = str_replace(['/', '\\'], '-', $document->code) . '_' . preg_replace('/[\/\\\?\%\*\:\|\\"\<\>\.\(\)]/', '_', $document->title) . '_(Signed)' . '.' . $fileExtension;
                Storage::disk('dokumen-approved')->put($fileName, file_get_contents($file));
                if (Storage::disk('dokumen-revision')->exists($documentRevision->file_path)) {
                    Storage::disk('dokumen-revision')->delete($documentRevision->file_path);
                }

                $documentRevision->update([
                    'file_path' => $fileName
                ]);

                $documentRevision->document->update([
                    'is_active' => true,
                    'current_revision_id' => $documentRevision->id,
                    'published_date' => now()->format('Y-m-d'),
                ]);                // Change status to Expired
                foreach ($documentRevision->revisedDocument() as $doc) {
                    $doc->currentRevision->update([
                        'status' => 'Expired'
                    ]);
                    $doc->update([
                        'is_active' => false,
                        'current_revision_id' => $documentRevision->id
                    ]);
                }

                for ($i = 1; $i < $documentRevision->revision_number; $i++) {
                    $rev = DocumentRevision::with('document')->where('document_id', $documentRevision->document_id)
                        ->where('revision_number', $i)->first();
                    $rev->update([
                        'status' => 'Expired'
                    ]);
                }

                event(
                    new NewApprovalDocument(
                        $documentRevision->document,
                        $roles,
                        'Dokumen ' . $documentRevision->document->title . ' Telah Disepakati.',
                        route('documents.show', ['document' => $documentRevision->document])
                    )
                );
            }

            DocumentHistory::create([
                'document_id' => $documentRevision->document_id,
                'revision_id' => $documentRevision->id,
                'action' => $act,
                'performed_by' => Auth::id(),
                'reason' => $validated['reason'] ?? null,
            ]);

            // Notifikasi dengan pesan spesifik dan link langsung
            $docTitle = $documentRevision->document->title;
            $docLink = route('document_approval.index', ['highlight' => $documentRevision->document->id]);

            // Tentukan redirect URL dan toast message berdasarkan role dan status
            $isKepalaPuskesmas = auth()->user()->isRole('Kepala-Puskesmas');
            $redirectRoute = $isKepalaPuskesmas ? 'document.active' : 'document_approval.index';
            $redirectParams = [];

            if ($uploadFileSigned) {
                // Dokumen sudah final disetujui - kirim notif ke uploader
                event(new \App\Events\DocumentStatusUpdated(
                    $documentRevision->document,
                    'approved',
                    'Dokumen "' . $docTitle . '" telah disetujui dan aktif',
                    $documentRevision->document->uploaded_by
                ));
                return redirect()->route($redirectRoute, $redirectParams)->with('success', 'Dokumen "' . $docTitle . '" berhasil disetujui dan telah aktif.');
            } else if ($documentRevision->acc_format && !$documentRevision->acc_content) {
                // Menunggu approval Bagian Mutu
                $message = 'Dokumen "' . $docTitle . '" menunggu persetujuan konten dari Anda';
                event(new NewApprovalDocument($documentRevision->document, [3], $message, $docLink));
                return redirect()->route($redirectRoute, $redirectParams)->with('success', 'Persetujuan format berhasil. Dokumen menunggu persetujuan konten dari Bagian Mutu.');
            } else if (!$documentRevision->acc_format && $documentRevision->acc_content) {
                // Menunggu approval Pengendali Dokumen
                $message = '[PENGECEKAN FORMAT] Dokumen "' . $docTitle . '" menunggu persetujuan format dari Anda';
                event(new NewApprovalDocument($documentRevision->document, [2], $message, $docLink));
                return redirect()->route($redirectRoute, $redirectParams)->with('success', 'Persetujuan konten berhasil. Dokumen menunggu persetujuan format dari Pengendali Dokumen.');
            } else if ($documentRevision->acc_format && $documentRevision->acc_content && $validated['status'] !== 'Disetujui') {
                // Kedua approval sudah true, menunggu upload file signed dari Pengendali Dokumen
                $message = '[UPLOAD SIGNED] Dokumen "' . $docTitle . '" siap untuk upload file yang sudah ditandatangani';
                event(new NewApprovalDocument($documentRevision->document, [2], $message, $docLink));
                return redirect()->route($redirectRoute, $redirectParams)->with('success', 'Dokumen telah disetujui. Menunggu upload file yang sudah ditandatangani dari Pengendali Dokumen.');
            } else if ($validated['status'] === 'Pengajuan Revisi') {
                // Dokumen ditolak, perlu revisi - kirim ke uploader
                $reason = $validated['reason'] ?? 'Tidak ada alasan spesifik';
                event(new \App\Events\DocumentStatusUpdated(
                    $documentRevision->document,
                    'revision',
                    'Dokumen "' . $docTitle . '" membutuhkan revisi. Alasan: ' . $reason,
                    $documentRevision->document->uploaded_by
                ));
                $message = 'Dokumen "' . $docTitle . '" Membutuhkan Revisi';
                $link = route('document_revision.edit', ['documentRevision' => $documentRevision->id]);
                event(new NewApprovalDocument($documentRevision->document, $roles, $message, $link));
                return redirect()->route($redirectRoute, $redirectParams)->with('info', 'Dokumen "' . $docTitle . '" telah diajukan untuk revisi. Menunggu proses revisi dari PJ Program.');
            }

            return redirect()->route($redirectRoute, $redirectParams)->with('success', 'Persetujuan dokumen berhasil diproses.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui status dokumen. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }

    public function destroy(DocumentRevision $documentRevision)
    {
        try {
            // Cek apakah user adalah uploader atau admin
            $isUploader = $documentRevision->checkUploaderRoles();
            $isAdmin = auth()->user()->isRole('Administrator');

            if (!$isUploader && !$isAdmin) {
                return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus dokumen ini.');
            }

            // Cek apakah dokumen sudah disetujui atau sedang dalam proses approval
            if ($documentRevision->status === 'Disetujui') {
                return redirect()->back()->with('error', 'Dokumen yang sudah disetujui tidak dapat dihapus. Dokumen aktif harus tetap tersimpan untuk arsip.');
            }

            if ($documentRevision->acc_format || $documentRevision->acc_content) {
                return redirect()->back()->with('error', 'Dokumen sedang dalam proses approval. Tidak dapat dihapus saat ini.');
            }

            // Hanya bisa hapus jika status Draft atau Pengajuan Revisi
            if (!in_array($documentRevision->status, ['Draft', 'Proses Revisi', 'Pengajuan Revisi'])) {
                return redirect()->back()->with('error', 'Hanya dokumen dengan status Draft, Proses Revisi, atau Pengajuan Revisi yang dapat dihapus.');
            }

            $document = $documentRevision->document;
            $documentTitle = $document->title;

            // Hapus file dari storage jika ada
            if (Storage::disk('dokumen-revision')->exists($documentRevision->file_path)) {
                Storage::disk('dokumen-revision')->delete($documentRevision->file_path);
            }

            // Hapus semua revision history terkait dokumen ini
            DocumentHistory::where('document_id', $document->id)->delete();

            // Hapus semua revision terkait dokumen ini
            DocumentRevision::where('document_id', $document->id)->delete();

            // Hapus dokumen utama
            $document->delete();

            return redirect()->route('document_revision.index')->with('success', 'Dokumen "' . $documentTitle . '" berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus dokumen. Silakan coba lagi. Error: ' . $e->getMessage());
        }
    }
}
