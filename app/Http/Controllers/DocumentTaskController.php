<?php

namespace App\Http\Controllers;

use App\Models\DocumentTask;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Itstructure\LaRbac\Models\Role;

class DocumentTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $userRoleIds = $user->roles->pluck('id');

        if ($user->isRole('Kepala-Puskesmas') || $user->isRole('Administrator')) {
            // Kepala Puskesmas or Admin can see all tasks they created or all tasks
            $tasks = DocumentTask::with(['assigner', 'targetRole', 'assignedUser', 'referenceDocument'])
                ->orderBy('created_at', 'desc')
                ->get();
        } else {
            // Other roles see tasks assigned to their role or themselves
            $tasks = DocumentTask::with(['assigner', 'targetRole', 'assignedUser', 'referenceDocument'])
                ->whereIn('target_role_id', $userRoleIds)
                ->orWhere('assigned_user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $roles = Role::whereIn('slug', ['pj-program', 'staff', 'bagian-mutu'])->get();

        return view('admin.document_tasks.index', compact('tasks', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'instruction' => 'required|string',
            'target_role_id' => 'required|exists:roles,id',
            'task_type' => 'required|in:Baru,Revisi',
            'document_id' => 'required_if:task_type,Revisi|nullable|exists:documents,id',
        ]);

        $task = DocumentTask::create([
            'assigner_id' => Auth::id(),
            'target_role_id' => $validated['target_role_id'],
            'document_id' => $validated['document_id'],
            'task_type' => $validated['task_type'],
            'title' => $validated['title'],
            'instruction' => $validated['instruction'],
            'status' => 'Menunggu Ketersediaan',
        ]);

        return redirect()->back()->with('success', 'Penugasan berhasil dibuat.');
    }

    /**
     * Update the task status.
     */
    public function updateStatus(Request $request, DocumentTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:Dikerjakan,Selesai',
        ]);

        $user = Auth::user();

        // Security check: Only users with the target role can accept the task
        if ($validated['status'] === 'Dikerjakan') {
            if (!$user->roles->contains('id', $task->target_role_id)) {
                return redirect()->back()->with('error', 'Anda tidak memiliki otoritas untuk mengambil tugas ini.');
            }
            
            $task->update([
                'status' => 'Dikerjakan',
                'assigned_user_id' => $user->id,
            ]);

            return redirect()->back()->with('success', 'Tugas berhasil diterima. Selamat bekerja!');
        }

        if ($validated['status'] === 'Selesai') {
            // Only the assigned user or assigner can mark as finished
            if ($task->assigned_user_id !== $user->id && $task->assigner_id !== $user->id && !$user->isRole('Administrator')) {
                return redirect()->back()->with('error', 'Anda tidak memiliki otoritas untuk menyelesaikan tugas ini.');
            }

            $task->update(['status' => 'Selesai']);
            return redirect()->back()->with('success', 'Tugas ditandai sebagai selesai.');
        }

        return redirect()->back();
    }
}
