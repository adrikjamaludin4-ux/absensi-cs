<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'latitude',
        'longitude',
        'accuracy',
        'distance',
        'photo_path',
        'foto_masuk',
        'foto_pulang',
        'lokasi_masuk',
        'lokasi_pulang',
        'status_masuk',
        'status_pulang',
        'status_face',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * URL Foto Masuk
     */
    public function getFotoMasukUrlAttribute(): ?string
    {
        $path = $this->foto_masuk ?: $this->photo_path;
        return $path ? asset('storage/' . $path) : null;
    }

    /**
     * URL Foto Pulang
     */
    public function getFotoPulangUrlAttribute(): ?string
    {
        return $this->foto_pulang ? asset('storage/' . $this->foto_pulang) : null;
    }
}