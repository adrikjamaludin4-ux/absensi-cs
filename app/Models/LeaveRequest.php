<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'kategori',
        'alasan',
        'lampiran',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * URL Lampiran Bukti Surat/Foto.
     */
    public function getLampiranUrlAttribute(): ?string
    {
        return $this->lampiran ? asset('storage/' . $this->lampiran) : null;
    }
}
