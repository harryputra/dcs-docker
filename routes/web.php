<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentHistoryController;
use App\Http\Controllers\DocumentRevisionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CustomUserController;
use App\Http\Controllers\CustomRoleController;
use App\Http\Controllers\CustomPermissionController;
use App\Notifications\NewUserPasswordChange;

Route::get('/', function () {
    // Dynamic counters for landing page
    $dokumenAktif = \App\Models\Document::where('is_active', true)->count();
    $kategori = \App\Models\Category::count();
    $aktivitas = \App\Models\DocumentHistory::count();
    
    return view('welcome', compact('dokumenAktif', 'kategori', 'aktivitas'));
});

Route::get('/dashboards', function () {
    return view('admin.dashboard');
});

// Interceptor for 419 Page Expired back-button loops
Route::get('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

// ================================ BE ROUTE =============================

Route::middleware(['auth'])->group(function () {

    // Override package RBAC delete routes to use custom controllers that return flash messages
    Route::post('rbac/users/delete', [CustomUserController::class, 'delete'])->name('delete_user');
    Route::post('rbac/roles/delete', [CustomRoleController::class, 'delete'])->name('delete_role');
    Route::post('rbac/permissions/delete', [CustomPermissionController::class, 'delete'])->name('delete_permission');

    Route::get('/active_document', [DocumentController::class, 'indexActive'])->name('document.active');

    // Route::middleware(['role:Admin'])->group(function () {
    //     Route::get('/users/create', [UserController::class, 'create'])->name('create_users');
    //     Route::post('rbac/users/store', [UserController::class, 'store'])->name('store_user');
    //     Route::get('/document_histories', [DocumentHistoryController::class, 'index'])->name('document_histories.index');
    //     Route::get('/document_histories/{document_history}', [DocumentHistoryController::class, 'show'])->name('document_histories.show');
    //     Route::resource('categories', CategoryController::class);
    //     Route::resource('rbac/roles', \Itstructure\LaRbac\Http\Controllers\RoleController::class);
    //     Route::resource('rbac/permissions', \Itstructure\LaRbac\Http\Controllers\RoleController::class);
    //     Route::resource('rbac/users', \Itstructure\LaRbac\Http\Controllers\RoleController::class);

    // });

    Route::get('/dashboards', function () {
        return view('admin.dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notification.markRead');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    Route::get('/active_document', [DocumentController::class, 'indexActive'])->name('document.active')->middleware('can:active-document');
    Route::get('/dashboard', [DocumentController::class, 'dashboard'])->name('dashboard');

    Route::get('/users/create', [UserController::class, 'create'])->name('create_users')->middleware('can:create-users');
    Route::post('rbac/users/store', [UserController::class, 'store'])->name('store_user')->middleware('can:create-users');

    Route::get('/document_histories', [DocumentHistoryController::class, 'index'])->name('document_histories.index')->middleware('can:view-histories');
    Route::get('/document_histories/{document_history}', [DocumentHistoryController::class, 'show'])->name('document_histories.show')->middleware('can:view-histories');




    Route::middleware('can:manage-categories')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
        Route::delete('/categories-bulk-delete', [CategoryController::class, 'bulkDelete'])->name('categories.bulkDelete');
        Route::post('/categories-count-documents', [CategoryController::class, 'countDocuments'])->name('categories.countDocuments');
    });

    // Classification Routes
    Route::middleware('can:manage-categories')->group(function () {
        Route::resource('classifications', \App\Http\Controllers\ClassificationController::class);
    });

    // Classification API endpoint (tanpa middleware untuk akses dropdown)
    Route::get('/api/classifications/all', [\App\Http\Controllers\ClassificationController::class, 'getAllActive'])->name('api.classifications.all');

    Route::get('/document_revision', [DocumentRevisionController::class, 'index'])->name('document_revision.index')->middleware('can:view-revisions');
    Route::get('/document_approval', [DocumentRevisionController::class, 'indexApproval'])->name('document_approval.index')->middleware('can:view-approval');
    Route::get('/document_revision/{documentRevision}/edit', [DocumentRevisionController::class, 'edit'])->name('document_revision.edit')->middleware('can:edit-revisions');
    Route::get('/document_approval/{documentRevision}/edit', [DocumentRevisionController::class, 'editApproval'])->name('document_approval.edit')->middleware('can:edit-approval');
    Route::put('/document_revision/{documentRevision}', [DocumentRevisionController::class, 'update'])->name('document_revision.update')->middleware('can:edit-revisions');
    Route::delete('/document_revision/{documentRevision}', [DocumentRevisionController::class, 'destroy'])->name('document_revision.destroy')->middleware('can:delete-documents');
    Route::get('/document_revision/detail/{documentRevision}', [DocumentRevisionController::class, 'show'])->name('document_revision.show')->middleware('can:edit-revisions');
    Route::get('/document_revision/data', [DocumentRevisionController::class, 'getDoc'])->name('documents.get.document')->middleware('can:edit-approval');
    Route::put('/document_approval/{documentRevision}', [DocumentRevisionController::class, 'updateApproval'])->name('document_approval.update')->middleware('can:edit-approval');


    Route::get('/document_revision/create', [DocumentRevisionController::class, 'create'])->name('document_revision.create')->middleware('can:create-revisions');
    Route::post('/document_revision/store', [DocumentRevisionController::class, 'store'])->name('document_revision.store')->middleware('can:create-revisions');


    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index')->middleware('can:view-documents');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create')->middleware('can:create-documents');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store')->middleware('can:create-documents');
    Route::get('/active_document/{document}/show', [DocumentController::class, 'show'])->name('documents.show')->middleware('can:view-documents');
    Route::get('/documents/{document}/edit', [DocumentController::class, 'edit'])->name('documents.edit')->middleware('can:edit-documents');
    Route::put('/documents/{document}', [DocumentController::class, 'update'])->name('documents.update')->middleware('can:edit-documents');
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy')->middleware('can:delete-documents');
    Route::get('/documents/download/{filename}', [DocumentController::class, 'downloadDocument'])->name('file.dokumen')->middleware('can:view-documents');
    Route::get('/file/dokumen/{filename}', [DocumentController::class, 'showFile'])->name('document_revision.show-file')->middleware('can:view-documents');
    Route::get('/view/dokumen/{filename}', [DocumentController::class, 'viewFile'])->name('document_revision.view-file')->middleware('can:view-documents');
    Route::post('/document/preview/{revision}', [DocumentController::class, 'previewFileByID'])->name('document.preview')->middleware('can:view-documents');
    Route::get('/documents_category', [DocumentController::class, 'getDocByCategory'])->name('document.getByCategory')->middleware('can:view-documents');

    // DEV MODE & DATABASE MANAGER (Requires Administrator Role)
    Route::post('/dev-mode/toggle', [\App\Http\Controllers\DevModeController::class, 'toggle'])->name('dev-mode.toggle');
    Route::get('/database-manager', [\App\Http\Controllers\DatabaseManagerController::class, 'index'])->name('db.index');
    Route::get('/database-manager/{table}', [\App\Http\Controllers\DatabaseManagerController::class, 'show'])->name('db.show');
    Route::get('/database-manager/{table}/create', [\App\Http\Controllers\DatabaseManagerController::class, 'create'])->name('db.create');
    Route::post('/database-manager/{table}', [\App\Http\Controllers\DatabaseManagerController::class, 'store'])->name('db.store');
    Route::get('/database-manager/{table}/{id}/edit', [\App\Http\Controllers\DatabaseManagerController::class, 'edit'])->name('db.edit');
    Route::put('/database-manager/{table}/{id}', [\App\Http\Controllers\DatabaseManagerController::class, 'update'])->name('db.update');
    Route::delete('/database-manager/{table}/{id}', [\App\Http\Controllers\DatabaseManagerController::class, 'destroy'])->name('db.destroy');

    // Document Task Routes
    Route::get('/document-tasks', [\App\Http\Controllers\DocumentTaskController::class, 'index'])->name('document-tasks.index');
    Route::post('/document-tasks', [\App\Http\Controllers\DocumentTaskController::class, 'store'])->name('document-tasks.store')->middleware('can:create-tasks');
    Route::put('/document-tasks/{task}/status', [\App\Http\Controllers\DocumentTaskController::class, 'updateStatus'])->name('document-tasks.update-status');
});
