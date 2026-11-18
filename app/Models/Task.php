<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'interval_type',
        'target_time',
        'assigned_to',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Petugas Cleaning Service yang ditugaskan khusus (jika ada).
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Seluruh laporan / submission pengerjaan tugas.
     */
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class);
    }

    /**
     * Mengambil submission hari ini untuk user tertentu (atau umum).
     */
    public function todaySubmission($date = null, $userId = null)
    {
        $date = $date ?? \Carbon\Carbon::now('Asia/Jakarta')->toDateString();

        $query = $this->submissions()->where('tanggal', $date);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->latest()->first();
    }
}
