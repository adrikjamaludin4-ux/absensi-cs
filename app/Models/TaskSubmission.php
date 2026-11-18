<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'tanggal',
        'jam_submit',
        'status',
        'photo_proof',
        'notes',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * URL Foto Bukti Pengerjaan
     */
    public function getPhotoProofUrlAttribute(): ?string
    {
        return $this->photo_proof ? asset('storage/' . $this->photo_proof) : null;
    }
}
