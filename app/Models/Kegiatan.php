<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kegiatan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tanggal',
        'kegiatan',
        'foto',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    /**
     * Accessor untuk format tanggal formal Bahasa Indonesia.
     * Contoh: "Selasa, 01 September 2026"
     */
    public function getTanggalFormattedAttribute(): string
    {
        if (!$this->tanggal) {
            return '-';
        }

        return Carbon::parse($this->tanggal)->locale('id')->translatedFormat('l, d F Y');
    }

    /**
     * Accessor untuk URL publik foto.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }

        return Storage::url($this->foto);
    }
}
