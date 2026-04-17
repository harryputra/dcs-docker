<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Itstructure\LaRbac\Models\Role;

class DocumentTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'assigner_id',
        'target_role_id',
        'assigned_user_id',
        'document_id',
        'task_type',
        'title',
        'instruction',
        'status',
    ];

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigner_id');
    }

    public function targetRole()
    {
        return $this->belongsTo(Role::class, 'target_role_id');
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    public function referenceDocument()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }
}
