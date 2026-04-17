<?php

namespace App\Http\Controllers;

use App\Events\NewCreatedDocument;
use App\Models\Document;
use App\Models\DocumentRevision;
use App\Models\DocumentHistory;
use App\Models\Category;
use App\Models\User;
use App\Notifications\DocumentApprovalNotification;
use App\Notifications\DocumentCreatedNotification;
use Illuminate\Http\Request;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function downloadDocument($filename)
    {
        /** @var FilesystemAdapter $filesystem */
        $filesystem = Storage::disk('dokumen');

        if (!$filesystem->exists($filename)) {
            abort(404, 'File tidak ditemukan.');
        }

        // Mengunduh file menggunakan Storage::download()
        return $filesystem->download($filename);
    }

    public function showFile($filename)
    {
        // Cek apakah file adalah dokumen yang sudah disetujui (Signed)
        if (str_contains($filename, '_(Signed)')) {
            if (Storage::disk('dokumen-approved')->exists($filename)) {
                $filePath = Storage::disk('dokumen-approved')->path($filename);
            } elseif (Storage::disk('dokumen-revision')->exists($filename)) {
                $filePath = Storage::disk('dokumen-revision')->path($filename);
            } else {
                abort(404, 'File tidak ditemukan.');
            }
        } elseif (Storage::disk('dokumen-revision')->exists($filename)) {
            $filePath = Storage::disk('dokumen-revision')->path($filename);
        } else {
            abort(404, 'File tidak ditemukan.');
        }

        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan di sistem.');
        }

        $mimeType = mime_content_type($filePath);

        return Response::file($filePath, [
            'Content-Type' => $mimeType
        ]);
    }

    /**
     * Preview file by revision ID using a JSON-wrapped Base64 stream.
     * This is the 'Nuclear Option' to bypass IDM and other download managers
     * because they cannot intercept file data hidden inside a JSON response.
     */
    public function previewFileByID($id)
    {
        $revision = DocumentRevision::findOrFail($id);
        $filename = $revision->file_path;

        if (str_contains($filename, '_(Signed)')) {
            if (Storage::disk('dokumen-approved')->exists($filename)) {
                $fileContent = Storage::disk('dokumen-approved')->get($filename);
            } elseif (Storage::disk('dokumen-revision')->exists($filename)) {
                $fileContent = Storage::disk('dokumen-revision')->get($filename);
            } else {
                abort(404, 'File tidak ditemukan.');
            }
        } elseif (Storage::disk('dokumen-revision')->exists($filename)) {
            $fileContent = Storage::disk('dokumen-revision')->get($filename);
        } else {
            abort(404, 'File tidak ditemukan.');
        }

        if (!$fileContent) {
            abort(404, 'Konten file tidak ditemukan.');
        }

        // Return JSON with Base64 data to fully bypass IDM interception
        return response()->json([
            'success' => true,
            'content' => base64_encode($fileContent),
            'mime' => 'application/pdf',
            'name' => basename($filename)
        ]);
    }

    public function viewFile($filename)
    {
        // Cek apakah file adalah dokumen yang sudah disetujui (Signed)
        if (str_contains($filename, '_(Signed)')) {
            if (Storage::disk('dokumen-approved')->exists($filename)) {
                $fileUrl = Storage::disk('dokumen-approved')->url($filename);
            } elseif (Storage::disk('dokumen-revision')->exists($filename)) {
                $fileUrl = Storage::disk('dokumen-revision')->url($filename);
            } else {
                abort(404, 'File tidak ditemukan.');
            }
        } elseif (Storage::disk('dokumen-revision')->exists($filename)) {
            $fileUrl = Storage::disk('dokumen-revision')->url($filename);
        } else {
            abort(404, 'File tidak ditemukan.');
        }

        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

        return view('admin.documents.viewer', compact('fileUrl', 'extension', 'filename'));
    }


    public function getDocByCategory(Request $request)
    {
        $query = $request->input('q');
        $id = $request->input('id');
        $oldIds = $request->input('ids');
        $category_id = $request->input('categoryID');
        // dd($request);
        $documents = Document::where('is_active', '=', true)
            ->whereHas('uploader.roles', function ($query) {
                $query->whereIn('id', auth()->user()->roles->pluck('id'));
            })
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', '%' . $query . '%')
                    ->orWhere('code', 'like', '%' . $query . '%');
            })
            ->with(['category', 'currentRevision']);

        // Check agar document yang direvisi tidak muncul
        if ($id) {
            $documents = $documents->where('id', '!=', $id);
        }
        // Check agar document sesuai dengan kategori
        if ($category_id) {
            $documents = $documents->where('category_id', $category_id);
        }
        // Check agar dokumen yang direvisi muncul di input
        if ($oldIds) {
            $idsArray = explode(',', $oldIds);
            $documents = $documents->whereIn('id', $idsArray)
                ->whereHas('currentRevision', function ($query) {
                    $query->whereIn('status', ['Disetujui', 'Proses Revisi']);
                });
        } else {
            $documents = $documents->whereHas('currentRevision', function ($query) {
                $query->whereIn('status', ['Disetujui']);
            });
        }

        $documents = $documents->get();

        return response()->json($documents);
    }

    public function dashboard()
    {
        $roles = Auth::user()->roles->pluck('name')->toArray();
        $commonRoles = array_intersect(['Administrator', 'Pengendali Dokumen', 'Bagian Mutu', 'Kepala Puskesmas', 'Staff'], $roles);
        $categories = \App\Models\Category::orderBy('name')->get();

        // PJ Program data
        if (empty($commonRoles)) {
            $totalDocs = Document::whereHas('uploader.roles', function ($query) {
                $query->whereIn('id', auth()->user()->roles->pluck('id'));
            })->with(['uploader', 'latestHistory'])->count();
            $totalApprovedDocs = Document::whereHas('uploader.roles', function ($query) {
                $query->whereIn('id', auth()->user()->roles->pluck('id'));
            })->whereHas('revisions', function ($query) {
                $query->whereIn('status', ['Disetujui', 'Proses Revisi']);
            })->where('is_active', true)->count();
            $totalDeniedDocs = Document::whereHas('uploader.roles', function ($query) {
                $query->whereIn('id', auth()->user()->roles->pluck('id'));
            })->whereHas('revisions', function ($query) {
                $query->where('status', 'Expired');
            })->where('is_active', false)->count();
            $totalRevisedDocs = Document::whereHas('uploader.roles', function ($query) {
                $query->whereIn('id', auth()->user()->roles->pluck('id'));
            })->whereHas('revisions', function ($query) {
                $query->whereIn('status', ['Draft', 'Proses Revisi', 'Pengajuan Revisi']);
            })->where('is_active', false)->count();
            $documents = Document::whereHas('uploader.roles', function ($query) {
                $query->whereIn('id', auth()->user()->roles->pluck('id'));
            })->with(['revisions', 'currentRevision'])->orderBy('created_at', 'desc')->take(5)->get();
        } else {
            $totalDocs = Document::with('revisions')->count();
            $totalApprovedDocs = Document::whereHas('revisions', function ($query) {
                $query->whereIn('status', ['Disetujui', 'Proses Revisi']);
            })->where('is_active', true)->count();
            $totalDeniedDocs = Document::whereHas('revisions', function ($query) {
                $query->where('status', 'Expired');
            })->where('is_active', false)->count();
            $totalRevisedDocs = Document::whereHas('revisions', function ($query) {
                $query->where('status', 'Draft');
            })->where('is_active', false)->count();
            $documents = Document::with(['revisions', 'currentRevision'])->orderBy('created_at', 'desc')->take(5)->get();
        }

        return view('admin.home', compact('totalDocs', 'totalApprovedDocs', 'totalDeniedDocs', 'totalRevisedDocs', 'documents', 'categories'));
    }

    public function index()
    {
        $documents = Document::with(['category', 'uploader', 'currentRevision'])->paginate(10);

        return view('admin.documents.index', compact('documents'));
    }

    public function indexActive()
    {
        $userRoles = Auth::user()->roles->pluck('slug')->toArray();
        $documents = Document::whereHas('latestRevision', function ($query) {
            $query->whereNotIn('status', ['Draft', 'Pengajuan Revisi']);
        })->with(['category', 'uploader', 'latestRevision'])->get();
        $categories = \App\Models\Category::orderBy('name')->get();
        
        // Pass roles for the assignment modal
        $roles = \Itstructure\LaRbac\Models\Role::whereIn('slug', ['pj-program', 'staff', 'bagian-mutu'])->get();
        
        return view('admin.active_document.index', compact('documents', 'userRoles', 'categories', 'roles'));
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.documents.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $rules = [
                'title' => 'required|string|max:255',
                'category_id' => 'required|exists:categories,id',
                'description' => 'required|string',
                'created_at' => 'required|date|before_or_equal:today',
                'is_old_document' => 'nullable|boolean',
            ];

            // Tambahkan validasi khusus untuk dokumen lama
            if ($request->has('is_old_document') && $request->is_old_document == 1) {
                // Dokumen lama harus PDF
                $rules['file_path'] = 'required|file|mimes:pdf|max:20480';
                $rules['classification_id'] = 'required|exists:classifications,id';
                $rules['sequence_number'] = [
                    'required',
                    'integer',
                    'min:1',
                    \Illuminate\Validation\Rule::unique('documents', 'sequence_number')
                        ->where('classification_id', $request->classification_id)
                ];
                $rules['published_date'] = 'required|date|before_or_equal:today';
            } else {
                // Dokumen baru hanya boleh DOC, DOCX, XLS, XLSX
                $rules['file_path'] = 'required|file|mimes:doc,docx,xls,xlsx|max:20480';
            }

            if (auth()->user()->isRole('Administrator')) {
                $rules['noApproval'] = 'boolean';
            }

            $validated = $request->validate($rules);


            // Jika dokumen lama, set code dan is_active
            $isOldDoc = !empty($validated['is_old_document']);

            $docData = [
                'title' => $validated['title'],
                'code' => null, // Will be generated below for old docs
                'category_id' => $validated['category_id'],
                'uploaded_by' => Auth::id(),
                'current_revision_id' => null,
                'created_at' => $validated['created_at'],
                'is_active' => $isOldDoc ? 1 : 0,
            ];

            // Tambahkan field khusus untuk dokumen lama
            if ($isOldDoc) {
                $docData['published_date'] = $validated['published_date'];
                $docData['classification_id'] = $validated['classification_id'];
                $docData['sequence_number'] = (int) $validated['sequence_number']; // Cast ke integer
                $docData['puskesmas_code'] = 'PKM GRD';
            } else {
                $docData['published_date'] = null;
            }

            $file = $request->file('file_path');
            $fileExtension = $file->getClientOriginalExtension();

            // Mulai database transaction
            \DB::beginTransaction();

            // Create document first untuk bisa generate code
            $document = Document::create($docData);

            // Set created_at sesuai input user (override timestamp otomatis Laravel)
            $document->timestamps = false;
            $document->created_at = $validated['created_at'];
            $document->updated_at = now();
            $document->save();
            $document->timestamps = true;

            // Generate code untuk dokumen lama
            if ($isOldDoc) {
                $document->load(['classification', 'category']);
                $generatedCode = $document->generateDocumentCode();
                $document->code = $generatedCode;
                $document->save();
            }

            // Generate filename sesuai tipe dokumen
            $cleanTitle = preg_replace('/[\/\\\?\%\*\:\|\\"\<\>\.\(\)]/', '_', $validated['title']);
            
            if ($isOldDoc) {
                // Dokumen lama: gunakan code yang sudah di-generate dan berakhiran (Signed)
                $fileName = str_replace(['/', '\\'], '-', $document->code) . '_' . $cleanTitle . '_(Signed).' . $fileExtension;
                // Simpan langsung ke dokumen-approved karena sudah disahkan
                Storage::disk('dokumen-approved')->put($fileName, file_get_contents($file));
            } elseif (!empty($validated['noApproval'])) {
                // Admin tanpa approval: Gunakan Judul + Timestamp + (Signed)
                $document->is_active = $validated['noApproval'];
                $document->save();
                $fileName = $cleanTitle . '_' . time() . '_(Signed).' . $fileExtension;
                // Simpan ke dokumen-revision
                Storage::disk('dokumen-revision')->put($fileName, file_get_contents($file));
            } else {
                // Dokumen baru biasa: Gunakan Judul + Timestamp
                $fileName = $cleanTitle . '_' . time() . '.' . $fileExtension;
                // Simpan ke dokumen-revision untuk proses approval
                Storage::disk('dokumen-revision')->put($fileName, file_get_contents($file));
            }

            // Untuk dokumen lama, JANGAN ubah created_at document (biar tetap tanggal upload)

            $revDocData = [
                'document_id' => $document->id,
                'file_path' => $fileName,
                'revised_by' => Auth::id(),
                'revision_number' => 1,
                'description' => $validated['description'],
            ];

            // Auto-approve jika noApproval atau dokumen lama
            if (!empty($validated['noApproval'])) {
                $revDocData['status'] = $validated['noApproval'] == true ? 'Disetujui' : 'Draft';
                $revDocData['acc_format'] = $validated['noApproval'] == true ? 1 : 0;
                $revDocData['acc_content'] = $validated['noApproval'] == true ? 1 : 0;
            } elseif ($isOldDoc) {
                $revDocData['status'] = 'Disetujui';
                $revDocData['acc_format'] = 1;
                $revDocData['acc_content'] = 1;
            }

            $revision = DocumentRevision::create($revDocData);

            // Untuk dokumen lama, JANGAN ubah created_at revision (biar tetap tanggal upload)
            // History approval yang pakai published_date

            foreach ($validated['rev'] ?? [] as $rev) {
                $currentRevision = DocumentRevision::findOrFail($rev);

                DocumentRevision::create([
                    'document_id' => $rev,
                    'file_path' => $fileName,
                    'revised_by' => $validated['uploaded_by'],
                    'revision_number' => $currentRevision->revision_number + 1,
                    'description' => $validated['description'],
                ]);
            }

            $document->update(['current_revision_id' => $revision->id]);

            if (empty($validated['noApproval']) && !$isOldDoc) {
                DocumentHistory::create([
                    'document_id' => $document->id,
                    'revision_id' => $revision->id,
                    'action' => 'Created',
                    'performed_by' => Auth::id(),
                    'reason' => null,
                ]);
                // Load relations sebelum trigger event
                $document->load(['category', 'uploader']);
                event(new NewCreatedDocument($document, 'Dokumen "' . $document->title . '" telah dibuat oleh ' . Auth::user()->name));
            } elseif (!empty($validated['noApproval'])) {
                if ($validated['noApproval']) {
                    DocumentHistory::create([
                        'document_id' => $document->id,
                        'revision_id' => $revision->id,
                        'action' => 'Approved',
                        'performed_by' => Auth::id(),
                        'reason' => null,
                    ]);
                }
            } elseif ($isOldDoc) {
                // Dokumen lama: kirim notif ke admin untuk monitoring
                event(new \App\Events\OldDocumentUploaded($document, 'Dokumen lama "' . $document->title . '" berhasil diinput dan aktif (Nomor: ' . $document->code . ')'));

                // Jika dokumen lama, buat history untuk semua step approval
                // Step 1: Created - pakai created_at (tanggal upload)
                DocumentHistory::create([
                    'document_id' => $document->id,
                    'revision_id' => $revision->id,
                    'action' => 'Created',
                    'performed_by' => Auth::id(),
                    'reason' => null,
                ]);
                // History 'Created' tetap pakai created_at (otomatis dari timestamps)

                // Step 2: Pengecekan Format (Pengendali Dokumen) - pakai published_date
                $historyFormat = DocumentHistory::create([
                    'document_id' => $document->id,
                    'revision_id' => $revision->id,
                    'action' => 'Approved',
                    'performed_by' => Auth::id(),
                    'reason' => 'Auto-approved Format (dokumen lama yang sudah disahkan)',
                ]);
                $historyFormat->timestamps = false;
                $historyFormat->created_at = $validated['published_date'];
                $historyFormat->updated_at = $validated['published_date'];
                $historyFormat->save();

                // Step 3: Pengecekan Konten (Bagian Mutu) - pakai published_date
                $historyContent = DocumentHistory::create([
                    'document_id' => $document->id,
                    'revision_id' => $revision->id,
                    'action' => 'Approved',
                    'performed_by' => Auth::id(),
                    'reason' => 'Auto-approved Content (dokumen lama yang sudah disahkan)',
                ]);
                $historyContent->timestamps = false;
                $historyContent->created_at = $validated['published_date'];
                $historyContent->updated_at = $validated['published_date'];
                $historyContent->save();

                // Step 4: Pengesahan (Upload signed file) - pakai published_date
                $historyApproved = DocumentHistory::create([
                    'document_id' => $document->id,
                    'revision_id' => $revision->id,
                    'action' => 'Approved',
                    'performed_by' => Auth::id(),
                    'reason' => 'Auto-approved Final (dokumen lama yang sudah disahkan)',
                ]);
                $historyApproved->timestamps = false;
                $historyApproved->created_at = $validated['published_date'];
                $historyApproved->updated_at = $validated['published_date'];
                $historyApproved->save();
            }

            // Commit transaction jika semua berhasil
            \DB::commit();

            // return redirect()->route('documents.index')->with('success', 'Document created successfully.');
            if ($isOldDoc) {
                return redirect()->route('document_revision.index')->with('success', 'Dokumen lama berhasil ditambahkan dan disetujui.');
            } else {
                return redirect()->route('document_revision.index')->with('success', 'Dokumen berhasil dibuat dan menunggu persetujuan.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Rollback transaction jika ada error
            \DB::rollBack();

            // Pesan error validation yang user-friendly
            $errors = $e->errors();
            if (isset($errors['sequence_number'])) {
                return redirect()->back()->with('error', 'Nomor urut dokumen sudah digunakan untuk klasifikasi ini. Silakan gunakan nomor urut yang berbeda.')->withInput();
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Rollback transaction jika ada error
            \DB::rollBack();

            // Pesan error yang user-friendly
            $errorMessage = 'Gagal membuat dokumen. ';

            if (str_contains($e->getMessage(), 'Duplicate entry')) {
                $errorMessage .= 'Nomor dokumen sudah ada dalam sistem. Silakan periksa kembali.';
            } elseif (str_contains($e->getMessage(), 'file') || str_contains($e->getMessage(), 'upload') || str_contains($e->getMessage(), 'mimes')) {
                if ($request->has('is_old_document') && $request->is_old_document == 1) {
                    $errorMessage .= 'Format file tidak sesuai. Dokumen lama harus dalam format PDF. Maksimal 20MB.';
                } else {
                    $errorMessage .= 'Format file tidak sesuai. Dokumen baru harus dalam format DOC, DOCX, XLS, atau XLSX. Maksimal 20MB.';
                }
            } else {
                $errorMessage .= 'Terjadi kesalahan pada sistem. Silakan coba lagi atau hubungi administrator.';
            }

            return redirect()->back()->with('error', $errorMessage)->withInput();
        }
    }

    public function show(Document $document)
    {
        if (in_array($document->latestRevision()->first()->status, ['Draft', 'Pengajuan Revisi'])) {
            abort(404);
        }
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        $categories = Category::all();
        $users = User::all();
        return view('admin.documents.edit', compact('document', 'categories', 'users'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'file_path' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'description' => 'required|string',
            'noApproval' => 'nullable|boolean',
        ]);

        // Update data dokumen (tanpa code)
        $document->update([
            'title' => $validated['title'],
            'category_id' => $validated['category_id'],
        ]);

        // Update deskripsi di revision
        if ($document->currentRevision) {
            $document->currentRevision->update([
                'description' => $validated['description'],
            ]);
        }

        // Update file jika ada upload baru
        if ($request->hasFile('file_path')) {
            // Hapus file lama jika ada
            if ($document->currentRevision && $document->currentRevision->file_path) {
                // Coba hapus dari berbagai disk
                if (Storage::disk('dokumen')->exists($document->currentRevision->file_path)) {
                    Storage::disk('dokumen')->delete($document->currentRevision->file_path);
                } elseif (Storage::disk('dokumen-revision')->exists($document->currentRevision->file_path)) {
                    Storage::disk('dokumen-revision')->delete($document->currentRevision->file_path);
                }
            }

            // Simpan file baru ke disk dokumen-revision (karena masih draft)
            $path = $request->file('file_path')->store('', 'dokumen-revision');

            // Update file_path di revision
            $document->currentRevision->update([
                'file_path' => $path,
            ]);
        }

        // Jika admin centang "Tanpa Approval", langsung approve
        if (auth()->user()->isRole('Administrator') && $request->noApproval) {
            $document->currentRevision->update([
                'status' => 'Disetujui',
                'acc_format' => true,
                'acc_content' => true,
            ]);

            // Buat history untuk approval langsung
            DocumentHistory::create([
                'document_id' => $document->id,
                'revision_id' => $document->currentRevision->id,
                'action' => 'Approved',
                'performed_by' => auth()->id(),
                'reason' => 'Approved by Administrator without approval process',
            ]);
        }

        return redirect()->route('document_revision.index')->with('success', 'Dokumen berhasil diperbarui.');
    }


    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }

    //Approved Notify
    public function approveDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->status = 'Approved';
        $document->save();

        // event(new DocumentApprovalNotification($document));

        return response()->json(['message' => 'Document approved successfully']);
    }

    //Approved Notify
    public function createdDocument(Request $request, $id)
    {
        $document = Document::findOrFail($id);
        $document->status = 'Created';
        $document->save();

        event(new DocumentCreatedNotification($document));

        return response()->json(['message' => 'Document created successfully']);
    }

    /**
     * Get all level 1 classifications
     */
    public function getClassifications(Request $request)
    {
        $level = $request->get('level', 1);

        $classifications = \App\Models\DocumentClassification::where('level', $level)
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'code', 'name', 'parent_id']);

        return response()->json($classifications);
    }

    /**
     * Get children of a specific classification
     */
    public function getClassificationChildren($parentId)
    {
        $classifications = \App\Models\DocumentClassification::where('parent_id', $parentId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get(['id', 'code', 'name', 'parent_id', 'level']);

        return response()->json($classifications);
    }
}
