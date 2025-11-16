<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $fillable = [
        'kode_klasifikasi',
        'nama_klasifikasi',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class, 'classification_id');
    }
}
