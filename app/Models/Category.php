<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * Pilihan icon & warna badge yang tersedia. Dipakai di form <select> dan validasi.
     */
    public const ICONS = ['utensils', 'drink', 'cake', 'dumpling'];
    public const COLORS = ['orange', 'teal'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'image',
        'icon',
        'color',
        'items_count',
    ];

    /**
     * URL publik ke gambar cover (null kalau belum ada gambar).
     */
    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}